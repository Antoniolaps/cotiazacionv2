<?php
function index() {
    Auth::requireRole(['admin','vendedor']);
    $rows = DB::conn()->query(
      "SELECT v.*, c.nombre cliente, u.nombre vendedor
       FROM ventas v
       LEFT JOIN clientes c ON c.id=v.cliente_id
       LEFT JOIN usuarios u ON u.id=v.vendedor_id
       ORDER BY v.fecha_venta DESC LIMIT 300"
    )->fetchAll();
    $title = 'Facturas';
    require __DIR__ . '/../views/ventas/index.php';
}

function punto_venta() {
    Auth::requireRole(['admin','vendedor']);
    $productos = DB::conn()->query(
      "SELECT p.id, p.codigo, p.nombre, p.precio_venta, COALESCE(i.stock_actual,0) stock
       FROM productos p LEFT JOIN inventario i ON i.producto_id=p.id
       WHERE p.activo=1 ORDER BY p.nombre"
    )->fetchAll();
    $clientes = DB::conn()->query("SELECT id, codigo, nombre FROM clientes WHERE activo=1 ORDER BY nombre")->fetchAll();
    $title = 'Punto de Venta';
    require __DIR__ . '/../views/ventas/pos.php';
}

function registrar() {
    Auth::requireRole(['admin','vendedor']); csrf_check();
    $cliente_id = (int)($_POST['cliente_id'] ?? 0) ?: null;
    $items = json_decode($_POST['items'] ?? '[]', true);
    if (!$items) { flash('error','Carrito vacío'); redirect('?r=ventas/punto_venta'); }

    $db = DB::conn(); $db->beginTransaction();
    try {
        $subtotal = 0; $itbms_rate = cfg('itbms_rate');
        foreach ($items as $it) $subtotal += $it['cantidad'] * $it['precio'];
        $itbms = round($subtotal * $itbms_rate, 2);
        $total = $subtotal + $itbms;

        $factura = next_factura();
        $db->prepare("INSERT INTO ventas (numero_factura, cliente_id, vendedor_id, subtotal, itbms, total, estado)
                      VALUES (?,?,?,?,?,?, 'pendiente')")
           ->execute([$factura, $cliente_id, Auth::user()['id'], $subtotal, $itbms, $total]);
        $venta_id = (int)$db->lastInsertId();

        foreach ($items as $it) {
            $sub = $it['cantidad'] * $it['precio'];

            $cur = $db->query("SELECT COALESCE(stock_actual,0) s FROM inventario WHERE producto_id=" . (int)$it['id'])->fetch();
            $ant = (int)($cur['s'] ?? 0);
            $nuevo = $ant - (int)$it['cantidad'];
            if ($nuevo < 0) throw new Exception('Stock insuficiente: ' . $it['nombre']);

            $db->prepare("INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?,?,?,?,?)")
               ->execute([$venta_id, $it['id'], $it['cantidad'], $it['precio'], $sub]);
        }
        Activity::log('INSERT','ventas',$venta_id,null,['factura'=>$factura,'total'=>$total]);
        $db->commit();
        flash('success', "Factura $factura registrada");
        redirect('?r=ventas/ver&id=' . $venta_id);
    } catch (Throwable $e) {
        $db->rollBack(); flash('error', $e->getMessage()); redirect('?r=ventas/punto_venta');
    }
}

function ver() {
    Auth::requireLogin();
    $id = (int)($_GET['id'] ?? 0);
    $v = DB::conn()->prepare("SELECT v.*, c.nombre cliente, c.cedula_ruc, c.direccion, u.nombre vendedor
                              FROM ventas v 
                              LEFT JOIN clientes c ON c.id=v.cliente_id
                              LEFT JOIN usuarios u ON u.id=v.vendedor_id 
                              WHERE v.id=?");
    $v->execute([$id]); $venta = $v->fetch();
    if (!$venta) { flash('error','No encontrada'); redirect('?r=ventas/index'); }
    $d = DB::conn()->prepare("SELECT d.*, p.nombre, p.codigo FROM detalle_ventas d JOIN productos p ON p.id=d.producto_id WHERE venta_id=?");
    $d->execute([$id]); $detalle = $d->fetchAll();
    $p = DB::conn()->prepare("SELECT * FROM pagos WHERE venta_id=? ORDER BY fecha_pago DESC"); $p->execute([$id]);
    $pagos = $p->fetchAll();
    $pagado = array_sum(array_column($pagos, 'monto'));
    $title = 'Factura ' . $venta['numero_factura'];
    require __DIR__ . '/../views/ventas/ver.php';
}

function anular() {
    Auth::requireRole(['admin']); csrf_check();
    $id = (int)$_POST['id'];
    DB::conn()->prepare("UPDATE ventas SET estado='anulada' WHERE id=?")->execute([$id]);
    flash('success','Factura anulada (nota: el stock no se devuelve automáticamente)');
    redirect('?r=ventas/ver&id=' . $id);
}

function cotizaciones() {
    Auth::requireRole(['admin', 'vendedor']);
    $rows = DB::conn()->query(
      "SELECT v.*, c.nombre cliente, u.nombre vendedor
       FROM ventas v
       LEFT JOIN clientes c ON c.id=v.cliente_id
       LEFT JOIN usuarios u ON u.id=v.vendedor_id
       WHERE v.estado = 'cotizacion'
       ORDER BY v.fecha_venta DESC LIMIT 300"
    )->fetchAll();
    $title = 'Cotizaciones';
    require __DIR__ . '/../views/ventas/cotizaciones_list.php';
}

function cotizacion_create() {
    Auth::requireRole(['admin', 'vendedor']);
    $clientes = DB::conn()->query("SELECT id, codigo, nombre FROM clientes WHERE activo=1 ORDER BY nombre")->fetchAll();
    $productos = DB::conn()->query("SELECT id, codigo, nombre, precio_venta FROM productos WHERE activo=1 ORDER BY nombre")->fetchAll();
    $proveedores = DB::conn()->query("SELECT id, nombre FROM proveedores WHERE activo=1 ORDER BY nombre")->fetchAll();
    $title = 'Nueva Cotización';
    require __DIR__ . '/../views/ventas/cotizaciones.php';
}

function cotizacion_store() {
    Auth::requireRole(['admin', 'vendedor']); 
    csrf_check();
    $cliente_id = (int)($_POST['cliente_id'] ?? 0);
    $cliente_nombre = $_POST['cliente_nombre'] ?? '';
    $items = json_decode($_POST['items'] ?? '[]', true);
    $obs = $_POST['observaciones'] ?? null;
    
    if (!$items) { flash('error','Sin productos'); redirect('?r=ventas/cotizacion_create'); }
    
    $db = DB::conn(); 
    $db->beginTransaction();
    try {
        if (!$cliente_id && $cliente_nombre) {
            $codigo = 'CLI-' . time() . rand(10, 99);
            $db->prepare("INSERT INTO clientes (codigo, nombre) VALUES (?, ?)")->execute([$codigo, $cliente_nombre]);
            $cliente_id = (int)$db->lastInsertId();
        }

        $sub = 0; 
        foreach ($items as $it) $sub += $it['cantidad'] * $it['precio'];
        
        $itbms = round($sub * cfg('itbms_rate'), 2);
        $total = $sub + $itbms;
        
        $last = $db->query("SELECT numero_factura FROM ventas WHERE numero_factura LIKE 'COT-%' ORDER BY id DESC LIMIT 1")->fetchColumn();
        $num = 1;
        if ($last && preg_match('/^COT-(\d+)$/', $last, $m)) {
            $num = (int)$m[1] + 1;
        }
        $numero = 'COT-' . str_pad($num, 6, '0', STR_PAD_LEFT);
        
        $db->prepare("INSERT INTO ventas (numero_factura, cliente_id, vendedor_id, estado, subtotal, itbms, total, observaciones)
                      VALUES (?,?,?, 'cotizacion', ?,?,?,?)")
           ->execute([$numero, $cliente_id, Auth::user()['id'], $sub, $itbms, $total, $obs]);
           
        $venta_id = (int)$db->lastInsertId();
        
        foreach ($items as $it) {
            $db->prepare("INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?,?,?,?,?)")
               ->execute([$venta_id, $it['id'], $it['cantidad'], $it['precio'], $it['cantidad']*$it['precio']]);
        }
        
        Activity::log('INSERT', 'cotizaciones', $venta_id, null, ['cotizacion' => $numero, 'total' => $total]);
        $db->commit();
        
        flash('success',"Cotización registrada ($numero)");
        redirect('?r=ventas/ver&id=' . $venta_id);
    } catch (Throwable $e) { 
        $db->rollBack(); 
        flash('error',$e->getMessage()); 
        redirect('?r=ventas/cotizacion_create'); 
    }
}

function convertir_cotizacion() {
    Auth::requireRole(['admin', 'vendedor']);
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $db = DB::conn();
    $db->beginTransaction();
    try {
        $stmt = $db->prepare("SELECT * FROM ventas WHERE id = ? AND estado = 'cotizacion'");
        $stmt->execute([$id]);
        $v = $stmt->fetch();
        if (!$v) throw new Exception("Cotización no encontrada o ya procesada.");

        // Cambiar estado a 'pendiente' (venta activa)
        $db->prepare("UPDATE ventas SET estado = 'pendiente' WHERE id = ?")->execute([$id]);

        // Procesar detalles para actualizar inventario y registrar movimientos
        $details = $db->prepare("SELECT * FROM detalle_ventas WHERE venta_id = ?");
        $details->execute([$id]);
        $items = $details->fetchAll();

        foreach ($items as $it) {
            $pid = (int)$it['producto_id'];
            $cant = (int)$it['cantidad'];

            $invStmt = $db->prepare("SELECT COALESCE(stock_actual, 0) s FROM inventario WHERE producto_id = ?");
            $invStmt->execute([$pid]);
            $cur = $invStmt->fetch();
            $stockAnt = (int)($cur['s'] ?? 0);
            $stockNuevo = $stockAnt - $cant;

            if ($stockNuevo < 0) {
                throw new Exception("Stock insuficiente para el producto ID $pid");
            }

            $db->prepare("INSERT INTO inventario (producto_id, stock_actual, stock_reservado) VALUES (?, ?, 0) ON DUPLICATE KEY UPDATE stock_actual = ?")
               ->execute([$pid, $stockNuevo, $stockNuevo]);

            $db->prepare("INSERT INTO movimientos_inventario (producto_id, usuario_id, tipo_movimiento, cantidad, venta_id, descripcion, stock_anterior, stock_nuevo) VALUES (?, ?, 'salida', ?, ?, 'Conversión de cotización a venta', ?, ?)")
               ->execute([$pid, Auth::user()['id'], $cant, $id, $stockAnt, $stockNuevo]);
        }

        Activity::log('UPDATE', 'ventas', $id, ['estado' => 'cotizacion'], ['estado' => 'pendiente']);
        $db->commit();
        flash('success', "Cotización " . $v['numero_factura'] . " convertida a venta exitosamente.");
    } catch (Throwable $e) {
        $db->rollBack();
        flash('error', $e->getMessage());
    }
    redirect('?r=ventas/ver&id=' . $id);
}