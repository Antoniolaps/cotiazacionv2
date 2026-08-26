<?php
function index() {
    Auth::requireRole(['admin','almacen']);
    $rows = DB::conn()->query(
      "SELECT o.*, p.nombre proveedor, u.nombre usuario
       FROM ordenes_compra o JOIN proveedores p ON p.id=o.proveedor_id
       LEFT JOIN usuarios u ON u.id=o.usuario_id ORDER BY o.fecha_orden DESC LIMIT 200"
    )->fetchAll();
    $title = 'Órdenes de compra';
    require __DIR__ . '/../views/compras/index.php';
}

function create() {
    Auth::requireRole(['admin','almacen']);
    $proveedores = DB::conn()->query("SELECT id, nombre FROM proveedores WHERE activo=1 ORDER BY nombre")->fetchAll();
    $productos = DB::conn()->query("SELECT id, codigo, nombre, precio_compra FROM productos WHERE activo=1 ORDER BY nombre")->fetchAll();
    $title = 'Nueva orden de compra';
    require __DIR__ . '/../views/compras/form.php';
}

function store() {
    Auth::requireRole(['admin','almacen']); csrf_check();
    $proveedor_id = (int)$_POST['proveedor_id'];
    $numero_factura = trim($_POST['numero_factura'] ?? '');
    $items = json_decode($_POST['items'] ?? '[]', true);
    $obs = $_POST['observaciones'] ?? null;
    if (!$items) { flash('error','Sin productos'); redirect('?r=compras/create'); }
    if (!$numero_factura) { flash('error','El número de factura es obligatorio'); redirect('?r=compras/create'); }
    $db = DB::conn(); $db->beginTransaction();
    try {
        $sub = 0; foreach ($items as $it) $sub += $it['cantidad'] * $it['precio'];
        $itbms = round($sub * cfg('itbms_rate'), 2);
        $total = $sub + $itbms;
        $numero = next_orden();
        
        $db->prepare("INSERT INTO ordenes_compra (numero_orden, numero_factura, proveedor_id, usuario_id, estado, subtotal, itbms, total, observaciones)
                      VALUES (?,?,?,?,'recibida',?,?,?,?)")
           ->execute([$numero, $numero_factura, $proveedor_id, Auth::user()['id'], $sub, $itbms, $total, $obs]);
        $oc_id = (int)$db->lastInsertId();
        
        foreach ($items as $it) {
            $db->prepare("INSERT INTO detalle_orden_compra (orden_compra_id, producto_id, cantidad_pedida, cantidad_recibida, precio_unitario, subtotal, estado) VALUES (?,?,?,?,?,?,'recibido')")
               ->execute([$oc_id, $it['id'], $it['cantidad'], $it['cantidad'], $it['precio'], $it['cantidad']*$it['precio']]);
            
            // Actualizar el precio de compra en el catálogo
            $db->prepare("UPDATE productos SET precio_compra=? WHERE id=?")->execute([$it['precio'], $it['id']]);
            
            // Actualizar inventario
            $cur = $db->query("SELECT COALESCE(stock_actual,0) s FROM inventario WHERE producto_id=" . (int)$it['id'])->fetch();
            $ant = (int)($cur['s'] ?? 0); 
            $nuevo = $ant + $it['cantidad'];
            if ($cur === false) {
                $db->prepare("INSERT INTO inventario (producto_id, stock_actual) VALUES (?,?)")->execute([$it['id'], $nuevo]);
            } else {
                $db->prepare("UPDATE inventario SET stock_actual=? WHERE producto_id=?")->execute([$nuevo, $it['id']]);
            }
            
            // Registrar movimiento
            $db->prepare("INSERT INTO movimientos_inventario (producto_id, usuario_id, tipo_movimiento, cantidad, orden_compra_id, descripcion, stock_anterior, stock_nuevo) VALUES (?,?,'entrada',?,?, 'Compra Directa', ?, ?)")
               ->execute([$it['id'], Auth::user()['id'], $it['cantidad'], $oc_id, $ant, $nuevo]);
        }
        $db->commit();
        flash('success',"Compra registrada y stock actualizado (OC: $numero)");
        redirect('?r=compras/ver&id=' . $oc_id);
    } catch (Throwable $e) { $db->rollBack(); flash('error',$e->getMessage()); redirect('?r=compras/create'); }
}

function ver() {
    Auth::requireRole(['admin','almacen']);
    $id = (int)($_GET['id'] ?? 0);
    $o = DB::conn()->prepare("SELECT o.*, p.nombre proveedor, u.nombre usuario FROM ordenes_compra o JOIN proveedores p ON p.id=o.proveedor_id LEFT JOIN usuarios u ON u.id=o.usuario_id WHERE o.id=?");
    $o->execute([$id]); $orden = $o->fetch();
    if (!$orden) { flash('error','No encontrada'); redirect('?r=compras/index'); }
    $d = DB::conn()->prepare("SELECT d.*, p.nombre, p.codigo FROM detalle_orden_compra d JOIN productos p ON p.id=d.producto_id WHERE orden_compra_id=?");
    $d->execute([$id]); $detalle = $d->fetchAll();
    $title = 'Orden ' . $orden['numero_orden'];
    require __DIR__ . '/../views/compras/ver.php';
}

function recibir() {
    Auth::requireRole(['admin','almacen']); csrf_check();
    $id = (int)$_POST['id'];
    $db = DB::conn(); $db->beginTransaction();
    try {
        $det = $db->query("SELECT * FROM detalle_orden_compra WHERE orden_compra_id=$id")->fetchAll();
        foreach ($det as $it) {
            $pendiente = $it['cantidad_pedida'] - $it['cantidad_recibida'];
            if ($pendiente <= 0) continue;
            // sumar stock
            $cur = $db->query("SELECT COALESCE(stock_actual,0) s FROM inventario WHERE producto_id=" . (int)$it['producto_id'])->fetch();
            $ant = (int)($cur['s'] ?? 0); $nuevo = $ant + $pendiente;
            if ($cur === false) $db->prepare("INSERT INTO inventario (producto_id, stock_actual) VALUES (?,?)")->execute([$it['producto_id'], $nuevo]);
            else $db->prepare("UPDATE inventario SET stock_actual=? WHERE producto_id=?")->execute([$nuevo, $it['producto_id']]);
            // Actualizar precio de compra
            $db->prepare("UPDATE productos SET precio_compra=? WHERE id=?")->execute([$it['precio_unitario'], $it['producto_id']]);
            $db->prepare("UPDATE detalle_orden_compra SET cantidad_recibida=cantidad_pedida, estado='recibido' WHERE id=?")->execute([$it['id']]);
            $db->prepare("INSERT INTO movimientos_inventario (producto_id, usuario_id, tipo_movimiento, cantidad, orden_compra_id, descripcion, stock_anterior, stock_nuevo) VALUES (?,?,'entrada',?,?,'Recepción OC',?,?)")
               ->execute([$it['producto_id'], Auth::user()['id'], $pendiente, $id, $ant, $nuevo]);
        }
        $db->prepare("UPDATE ordenes_compra SET estado='recibida' WHERE id=?")->execute([$id]);
        $db->commit();
        flash('success','Orden recibida y stock actualizado');
    } catch (Throwable $e) { $db->rollBack(); flash('error',$e->getMessage()); }
    redirect('?r=compras/ver&id=' . $id);
}

function exportar_excel() {
    Auth::requireRole(['admin','almacen']);
    $id = (int)($_GET['id'] ?? 0);
    $db = DB::conn();
    
    $o = $db->prepare("SELECT o.*, p.nombre proveedor FROM ordenes_compra o JOIN proveedores p ON p.id=o.proveedor_id WHERE o.id=?");
    $o->execute([$id]);
    $orden = $o->fetch();
    
    if (!$orden) {
        flash('error','No encontrada');
        redirect('?r=compras/index');
    }
    
    $d = $db->prepare("SELECT d.*, p.nombre, p.codigo, p.codigo_barras, p.precio_venta 
                       FROM detalle_orden_compra d 
                       JOIN productos p ON p.id=d.producto_id 
                       WHERE orden_compra_id=?");
    $d->execute([$id]);
    $detalle = $d->fetchAll();
    
    $filename = "compra_" . $orden['numero_orden'] . ".csv";
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    
    $out = fopen('php://output', 'w');
    // BOM para Excel
    fputs($out, "\xEF\xBB\xBF");
    
    // Encabezados Generales
    fputcsv($out, ['Proveedor', 'N° de referencia de ingreso', 'n° de compra'], ';');
    fputcsv($out, [
        $orden['proveedor'], 
        $orden['numero_factura'] ?? '', 
        $orden['numero_orden']
    ], ';');
    
    // Espacio en blanco
    fputcsv($out, [], ';');
    
    // Encabezados de Detalles
    fputcsv($out, [
        'codigo_barra', 
        'descripcion del producto', 
        'referencia', 
        'cantidad', 
        'precio_costo', 
        'precio_venta'
    ], ';');
    
    foreach ($detalle as $item) {
        fputcsv($out, [
            $item['codigo_barras'] ?? '',
            $item['nombre'],
            $item['codigo'] ?? '',
            $item['cantidad_pedida'], // O cantidad_recibida, pondré la pedida o recibida? "cantidad" suele ser la facturada/pedida
            $item['precio_unitario'],
            $item['precio_venta']
        ], ';');
    }
    
    fclose($out);
    exit;
}
