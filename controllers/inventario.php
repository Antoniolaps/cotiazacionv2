<?php
function index() {
    Auth::requireRole(['admin','almacen']);
    $rows = DB::conn()->query(
      "SELECT p.id, p.codigo, p.nombre, p.stock_minimo, COALESCE(i.stock_actual,0) AS stock_actual,
              COALESCE(i.stock_reservado,0) AS stock_reservado, i.ultima_actualizacion
       FROM productos p
       LEFT JOIN inventario i ON i.producto_id = p.id
       WHERE p.activo=1 ORDER BY p.nombre"
    )->fetchAll();
    $title = 'Inventario';
    require __DIR__ . '/../views/inventario/index.php';
}

function ajustar() {
    Auth::requireRole(['admin','almacen']);
    $id = (int)($_GET['id'] ?? 0);
    $p = DB::conn()->prepare("SELECT p.*, COALESCE(i.stock_actual,0) AS stock FROM productos p LEFT JOIN inventario i ON i.producto_id=p.id WHERE p.id=?");
    $p->execute([$id]); $producto = $p->fetch();
    if (!$producto) { flash('error','Producto no encontrado'); redirect('?r=inventario/index'); }
    $title = 'Ajuste de inventario';
    require __DIR__ . '/../views/inventario/ajuste.php';
}

function aplicar_ajuste() {
    Auth::requireRole(['admin','almacen']); csrf_check();
    $producto_id = (int)$_POST['producto_id'];
    $tipo = $_POST['tipo'];        // entrada|salida|ajuste
    $cantidad = (int)$_POST['cantidad'];
    $obs = $_POST['observaciones'] ?? null;

    $db = DB::conn(); $db->beginTransaction();
    try {
        $cur = $db->query("SELECT COALESCE(stock_actual,0) s FROM inventario WHERE producto_id=$producto_id")->fetch();
        $anterior = (int)($cur['s'] ?? 0);
        $nuevo = match($tipo) {
            'entrada'   => $anterior + $cantidad,
            'salida'    => $anterior - $cantidad,
            'ajuste'    => $cantidad,
            'devolucion'=> $anterior + $cantidad,
        };
        if ($nuevo < 0) throw new Exception('Stock no puede ser negativo');

        if ($cur === false) {
            $db->prepare("INSERT INTO inventario (producto_id, stock_actual) VALUES (?,?)")->execute([$producto_id, $nuevo]);
        } else {
            $db->prepare("UPDATE inventario SET stock_actual=? WHERE producto_id=?")->execute([$nuevo, $producto_id]);
        }
        $db->prepare(
          "INSERT INTO movimientos_inventario (producto_id, usuario_id, tipo_movimiento, cantidad, descripcion, stock_anterior, stock_nuevo, observaciones)
           VALUES (?,?,?,?,?,?,?,?)"
        )->execute([$producto_id, Auth::user()['id'], $tipo, $cantidad, 'Ajuste manual', $anterior, $nuevo, $obs]);
        $db->commit();
        flash('success','Inventario actualizado');
    } catch (Throwable $e) { $db->rollBack(); flash('error',$e->getMessage()); }
    redirect('?r=inventario/index');
}

function movimientos() {
    Auth::requireRole(['admin','almacen']);
    $rows = DB::conn()->query(
       "SELECT m.*, p.nombre producto, u.nombre usuario, 
               v.numero_factura AS factura_venta,
               oc.numero_factura AS factura_compra
        FROM movimientos_inventario m
        JOIN productos p ON p.id=m.producto_id
        LEFT JOIN usuarios u ON u.id=m.usuario_id
        LEFT JOIN ventas v ON v.id=m.venta_id
        LEFT JOIN ordenes_compra oc ON oc.id=m.orden_compra_id
        ORDER BY m.fecha_movimiento DESC LIMIT 200"
    )->fetchAll();
    $title = 'Movimientos de inventario';
    require __DIR__ . '/../views/inventario/movimientos.php';
}
