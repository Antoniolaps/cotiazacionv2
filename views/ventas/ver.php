<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="d-flex justify-content-between mb-3">
  <h3><?= $venta['estado'] === 'cotizacion' ? 'Cotización' : 'Factura' ?> <?= e($venta['numero_factura']) ?></h3>
  <div>
    <button class="btn btn-outline-dark d-print-none" onclick="window.print()"><i class="bi bi-printer"></i> Imprimir</button>
    <a class="btn btn-outline-secondary d-print-none" href="<?= url('?r=' . ($venta['estado'] === 'cotizacion' ? 'ventas/cotizaciones' : 'ventas/index')) ?>">← Volver</a>
    <?php if ($venta['estado'] === 'cotizacion'): ?>
      <form method="post" action="<?= url('?r=ventas/convertir_cotizacion') ?>" class="d-inline d-print-none" onsubmit="return confirm('¿Desea convertir esta cotización en una venta activa?')">
        <?= csrf_field() ?><input type="hidden" name="id" value="<?= e($venta['id']) ?>">
        <button class="btn btn-success"><i class="bi bi-cart-check"></i> Convertir a Venta</button>
      </form>
    <?php else: ?>
      <a class="btn btn-outline-primary d-print-none" href="<?= url('?r=pagos/registrar&venta_id='.$venta['id']) ?>"><i class="bi bi-credit-card"></i> Registrar pago</a>
    <?php endif; ?>
    <?php if (Auth::role()==='admin' && $venta['estado']!=='anulada'): ?>
    <form method="post" action="<?= url('?r=ventas/anular') ?>" class="d-inline d-print-none" onsubmit="return confirm('¿Anular <?= $venta['estado'] === 'cotizacion' ? 'cotización' : 'factura' ?>?')">
      <?= csrf_field() ?><input type="hidden" name="id" value="<?= e($venta['id']) ?>">
      <button class="btn btn-outline-danger">Anular</button>
    </form>
    <?php endif; ?>
  </div>
</div>
<div class="row"><div class="col-md-8"><div class="card p-3 mb-3">
  <div class="row"><div class="col"><small class="text-muted">Cliente</small><br><strong><?= e($venta['cliente'] ?? '—') ?></strong>
    <?php if (!empty($venta['cedula_ruc'])): ?><br><small>RUC: <?= e($venta['cedula_ruc']) ?></small><?php endif; ?></div>
    
    <?php if (!empty($venta['proveedor'])): ?>
    <div class="col"><small class="text-muted">Proveedor</small><br><strong><?= e($venta['proveedor']) ?></strong>
      <?php if (!empty($venta['factura_proveedor'])): ?><br><small>Fac: <?= e($venta['factura_proveedor']) ?></small><?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="col"><small class="text-muted">Fecha</small><br><?= e($venta['fecha_venta']) ?>
    <br><small class="text-muted">Vendedor: <?= e($venta['vendedor'] ?? '—') ?></small></div>
    <div class="col text-end"><span class="badge fs-6 bg-<?= $venta['estado']==='pagada'?'success':($venta['estado']==='anulada'?'danger':'warning') ?>"><?= e($venta['estado']) ?></span></div>
  </div></div>
  <div class="card p-3"><table class="table">
    <thead><tr><th>Código</th><th>Producto</th><th class="text-end">Cant</th><th class="text-end">Precio</th><th class="text-end">Subtotal</th></tr></thead>
    <tbody><?php foreach ($detalle as $d): ?>
      <tr><td><?= e($d['codigo']) ?></td><td><?= e($d['nombre']) ?></td>
      <td class="text-end"><?= e($d['cantidad']) ?></td><td class="text-end"><?= money($d['precio_unitario']) ?></td>
      <td class="text-end"><?= money($d['subtotal']) ?></td></tr>
    <?php endforeach; ?></tbody>
  </table>
  <div class="text-end">
    <div>Subtotal: <strong><?= money($venta['subtotal']) ?></strong></div>
    <div>ITBMS: <strong><?= money($venta['itbms']) ?></strong></div>
    <div class="h5">Total: <?= money($venta['total']) ?></div>
  </div></div>
</div>
<?php if ($venta['estado'] !== 'cotizacion'): ?>
<div class="col-md-4"><div class="card p-3">
  <h6>Pagos</h6>
  <?php if (!$pagos): ?><p class="text-muted small">Sin pagos registrados</p><?php endif; ?>
  <?php foreach ($pagos as $pg): ?>
    <div class="border-bottom py-2 small">
      <div class="d-flex justify-content-between"><strong><?= money($pg['monto']) ?></strong><span class="badge bg-info"><?= e($pg['metodo_pago']) ?></span></div>
      <div class="text-muted"><?= e($pg['fecha_pago']) ?></div>
      <?php if ($pg['referencia']): ?><div>Ref: <?= e($pg['referencia']) ?></div><?php endif; ?>
    </div>
  <?php endforeach; ?>
  <hr><div class="d-flex justify-content-between"><span>Pagado:</span><strong><?= money($pagado) ?></strong></div>
  <div class="d-flex justify-content-between"><span>Saldo:</span><strong class="text-<?= ($venta['total']-$pagado)<=0?'success':'danger' ?>"><?= money($venta['total']-$pagado) ?></strong></div>
</div></div>
<?php endif; ?>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
