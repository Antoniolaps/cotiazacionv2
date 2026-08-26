<?php
// controllers/pos.php  (router: ?r=pos/terminal, ?r=pos/api_productos, etc.)
require_once __DIR__ . '/../includes/auth.php';

/**
 * Muestra la terminal POS del vendedor.
 * Acceso: solo rol 'vendedor' (y admin para supervisión).
 */
function terminal() {
    Auth::requireRole(['vendedor', 'admin']);

    // Precargar categorías para los filtros del POS
    $categorias = DB::conn()->query(
        "SELECT id, nombre FROM categoria WHERE activo=1 ORDER BY nombre"
    )->fetchAll();

    // Precargar clientes activos
    $clientes = DB::conn()->query(
        "SELECT id, codigo, nombre FROM clientes WHERE activo=1 ORDER BY nombre"
    )->fetchAll();

    require __DIR__ . '/../views/pos/terminal.php';
}

/**
 * API JSON: buscar productos para el POS.
 * ?r=pos/api_productos  GET  q=term | barcode=xxx | categoria_id=N
 * Devuelve array de productos con stock > 0 (activos).
 */
function api_productos() {
    Auth::requireRole(['vendedor', 'admin']);
    header('Content-Type: application/json; charset=utf-8');

    $q           = trim($_GET['q'] ?? '');
    $barcode     = trim($_GET['barcode'] ?? '');
    $categoriaId = (int)($_GET['categoria_id'] ?? 0);

    $params = [];
    $where  = ["p.activo = 1", "COALESCE(i.stock_actual, 0) > 0"];

    if ($barcode !== '') {
        // Búsqueda exacta por código de barras (scanner)
        $where[] = "(p.codigo_barras = ? OR p.codigo = ?)";
        $params[] = $barcode;
        $params[] = $barcode;
    } elseif ($q !== '') {
        $like = "%$q%";
        $where[] = "(p.nombre LIKE ? OR p.codigo LIKE ? OR p.codigo_barras LIKE ?)";
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    if ($categoriaId > 0) {
        $where[] = "p.categoria_id = ?";
        $params[] = $categoriaId;
    }

    $sql = "SELECT p.id,
                   p.codigo,
                   p.codigo_barras,
                   p.nombre,
                   p.precio_venta,
                   p.unidad_medida,
                   c.nombre AS categoria_nombre,
                   COALESCE(i.stock_actual, 0) AS stock
            FROM productos p
            LEFT JOIN categoria c ON c.id = p.categoria_id
            LEFT JOIN inventario i ON i.producto_id = p.id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY p.nombre
            LIMIT 100";

    $stmt = DB::conn()->prepare($sql);
    $stmt->execute($params);
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
}

/**
 * API JSON: verificar stock de un producto en tiempo real.
 * ?r=pos/api_stock  GET  id=N
 */
function api_stock() {
    Auth::requireRole(['vendedor', 'admin']);
    header('Content-Type: application/json; charset=utf-8');

    $id = (int)($_GET['id'] ?? 0);
    if (!$id) { echo json_encode(['stock' => 0]); return; }

    $row = DB::conn()
        ->prepare("SELECT COALESCE(stock_actual, 0) AS stock FROM inventario WHERE producto_id = ?");
    $row->execute([$id]);
    $data = $row->fetch();
    echo json_encode(['stock' => (int)($data['stock'] ?? 0)]);
}

/**
 * API JSON: registrar venta desde la terminal POS.
 * ?r=pos/api_vender  POST
 * Body JSON: { cliente_id, metodo_pago, monto_recibido, items: [{id, nombre, cantidad, precio}] }
 */
function api_vender() {
    Auth::requireRole(['vendedor', 'admin']);
    csrf_check();
    header('Content-Type: application/json; charset=utf-8');

    $body       = raw_input_json();
    $clienteId  = (int)($body['cliente_id'] ?? 0) ?: null;
    $metodoPago = $body['metodo_pago'] ?? 'efectivo';
    $montoRecib = (float)($body['monto_recibido'] ?? 0);
    $items      = $body['items'] ?? [];

    if (empty($items)) {
        http_response_code(422);
        echo json_encode(['error' => 'Carrito vacío']);
        return;
    }

    if ($metodoPago === 'tarjeta') {
        $metodoPago = 'tarjeta_credito';
    }

    $metodosValidos = ['efectivo', 'tarjeta_credito', 'tarjeta_debito', 'transferencia', 'yappy', 'nequi', 'vale', 'gift_card', 'cheque', 'deposito'];
    if (!in_array($metodoPago, $metodosValidos, true)) {
        $metodoPago = 'efectivo';
    }

    $db = DB::conn();
    $db->beginTransaction();
    try {
        $itbmsRate = cfg('itbms_rate'); // 0.07
        $subtotal  = 0;

        foreach ($items as $it) {
            $subtotal += (float)$it['cantidad'] * (float)$it['precio'];
        }

        $itbms = round($subtotal * $itbmsRate, 2);
        $total = $subtotal + $itbms;

        // Validar que el monto recibido cubre el total (solo efectivo)
        if ($metodoPago === 'efectivo' && $montoRecib < $total - 0.005) {
            throw new Exception('Monto recibido insuficiente');
        }

        $factura = next_factura();

        $db->prepare(
            "INSERT INTO ventas (numero_factura, cliente_id, vendedor_id, subtotal, itbms, total, estado)
             VALUES (?, ?, ?, ?, ?, ?, 'pagada')"
        )->execute([$factura, $clienteId, Auth::user()['id'], $subtotal, $itbms, $total]);

        $ventaId = (int)$db->lastInsertId();

        foreach ($items as $it) {
            $pid      = (int)$it['id'];
            $cant     = (int)$it['cantidad'];
            $precio   = (float)$it['precio'];
            $subitem  = round($cant * $precio, 2);

            $inv = $db->prepare("SELECT COALESCE(stock_actual,0) AS s FROM inventario WHERE producto_id=?");
            $inv->execute([$pid]);
            $cur = $inv->fetch();
            $ant = (int)($cur['s'] ?? 0);

            if ($ant < $cant) {
                throw new Exception('Stock insuficiente para: ' . htmlspecialchars($it['nombre'] ?? "ID $pid"));
            }

            $db->prepare(
                "INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_unitario, subtotal)
                 VALUES (?, ?, ?, ?, ?)"
            )->execute([$ventaId, $pid, $cant, $precio, $subitem]);
        }

        $cambio = ($metodoPago === 'efectivo') ? max(0, $montoRecib - $total) : 0;

        // Registrar pago completo
        $db->prepare(
            "INSERT INTO pagos (venta_id, monto, moneda, monto_recibido, cambio, metodo_pago, estado, referencia, usuario_id)
             VALUES (?, ?, 'USD', ?, ?, ?, 'aprobado', ?, ?)"
        )->execute([$ventaId, $total, $montoRecib > 0 ? $montoRecib : $total, $cambio, $metodoPago, $factura, Auth::user()['id']]);

        Activity::log('INSERT', 'ventas', $ventaId, null, ['factura' => $factura, 'total' => $total]);

        $db->commit();

        $cambio = ($metodoPago === 'efectivo') ? max(0, $montoRecib - $total) : 0;

        echo json_encode([
            'ok'             => true,
            'factura'        => $factura,
            'venta_id'       => $ventaId,
            'total'          => $total,
            'cambio'         => round($cambio, 2),
            'url_factura'    => url('?r=ventas/ver&id=' . $ventaId),
        ]);
    } catch (Throwable $e) {
        $db->rollBack();
        http_response_code(422);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

/**
 * Pantalla del cliente (monitor secundario / dual screen).
 * Acceso público dentro de la red local.
 */
function customer_display() {
    require __DIR__ . '/../views/pos/customer_display.php';
}

/**
 * Vista simplificada para el cliente en auto-consulta.
 * Acceso: solo rol 'cliente'.
 */
function poscliente() {
    Auth::requireRole(['cliente']);
    require __DIR__ . '/../views/pos/Poscliente.php';
}
