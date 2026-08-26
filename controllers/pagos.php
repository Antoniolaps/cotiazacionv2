<?php
function index() {
    Auth::requireRole(['admin','vendedor']);
    $rows = DB::conn()->query(
      "SELECT p.*, v.numero_factura, c.nombre cliente
       FROM pagos p JOIN ventas v ON v.id=p.venta_id
       LEFT JOIN clientes c ON c.id=v.cliente_id
       ORDER BY p.fecha_pago DESC LIMIT 300"
    )->fetchAll();
    $title = 'Pagos';
    require __DIR__ . '/../views/pagos/index.php';
}

function registrar() {
    Auth::requireRole(['admin']);
    $venta_id = (int)($_GET['venta_id'] ?? 0);
    $v = DB::conn()->prepare("SELECT v.*, COALESCE(SUM(p.monto),0) pagado FROM ventas v LEFT JOIN pagos p ON p.venta_id=v.id WHERE v.id=? GROUP BY v.id");
    $v->execute([$venta_id]); $venta = $v->fetch();
    if (!$venta) { flash('error','Venta no encontrada'); redirect('?r=ventas/index'); }
    $saldo = $venta['total'] - $venta['pagado'];
    $title = 'Registrar pago';
    require __DIR__ . '/../views/pagos/form.php';
}

function store() {
    Auth::requireRole(['admin','vendedor']); csrf_check();
    $venta_id = (int)$_POST['venta_id'];
    $monto    = (float)$_POST['monto'];
    $metodo   = $_POST['metodo_pago'];
    $ref      = $_POST['referencia'] ?? null;
    $obs      = $_POST['observaciones'] ?? null;
    $userId   = Auth::user()['id'] ?? null;

    $metodosValidos = ['efectivo','tarjeta_credito','tarjeta_debito','transferencia','yappy','nequi','vale','gift_card','cheque','deposito'];
    if (!in_array($metodo, $metodosValidos, true)) {
        $metodo = 'efectivo';
    }

    $db = DB::conn(); $db->beginTransaction();
    try {
        $db->prepare(
            "INSERT INTO pagos (venta_id, monto, moneda, monto_recibido, cambio, metodo_pago, estado, referencia, usuario_id, observaciones)
             VALUES (?, ?, 'USD', ?, 0, ?, 'aprobado', ?, ?, ?)"
        )->execute([$venta_id, $monto, $monto, $metodo, $ref, $userId, $obs]);

        // Triggers trg_pagos_after_insert actualizan ventas.estado automáticamente, 
        // pero mantenemos respaldo explícito:
        $tot = $db->query("SELECT v.total, COALESCE(SUM(p.monto),0) pagado
                           FROM ventas v LEFT JOIN pagos p ON p.venta_id=v.id WHERE v.id=$venta_id AND p.estado='aprobado' GROUP BY v.id")->fetch();
        if ($tot) {
            $nuevo = ($tot['pagado'] >= $tot['total'] && $tot['total'] > 0) ? 'pagada' : 'parcial';
            $db->prepare("UPDATE ventas SET estado=? WHERE id=?")->execute([$nuevo, $venta_id]);
        }
        $db->commit();
        flash('success','Pago registrado correctamente');
    } catch (Throwable $e) { $db->rollBack(); flash('error',$e->getMessage()); }
    redirect('?r=ventas/ver&id=' . $venta_id);
}
