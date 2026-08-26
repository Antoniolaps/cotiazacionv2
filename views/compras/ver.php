<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="d-flex justify-content-between mb-3">
  <h3>Orden <?= e($orden['numero_orden']) ?></h3>
  <div>
    <a class="btn btn-success me-2" href="<?= url('?r=compras/exportar_excel&id='.$orden['id']) ?>">
      <i class="bi bi-file-earmark-excel"></i> Exportar a Excel
    </a>
    <a class="btn btn-outline-secondary" href="<?= url('?r=compras/index') ?>">← Volver</a>
  </div>
</div>
<div class="card p-3 mb-3"><div class="row g-3">
  <div class="col"><small class="text-muted">Proveedor</small><br><strong><?= e($orden['proveedor']) ?></strong></div>
  <div class="col"><small class="text-muted">Nº Factura</small><br><strong><?= e($orden['numero_factura'] ?? '—') ?></strong></div>
  <div class="col"><small class="text-muted">Fecha orden</small><br><?= e($orden['fecha_orden']) ?></div>
  <div class="col"><small class="text-muted">Registrado por</small><br><?= e($orden['usuario'] ?? '—') ?></div>
  <div class="col text-end"><span class="badge fs-6 bg-<?= $orden['estado']==='recibida'?'success':'warning' ?>"><?= e($orden['estado']) ?></span></div>
</div></div>
<div class="card p-3"><table class="table">
<thead><tr><th>Código</th><th>Producto</th><th class="text-end">Pedido</th><th class="text-end">Recibido</th><th class="text-end">Precio</th><th class="text-end">Subtotal</th></tr></thead>
<tbody><?php foreach ($detalle as $d): ?>
<tr><td><?= e($d['codigo']) ?></td><td><?= e($d['nombre']) ?></td>
<td class="text-end"><?= e($d['cantidad_pedida']) ?></td><td class="text-end"><?= e($d['cantidad_recibida']) ?></td>
<td class="text-end"><?= money($d['precio_unitario']) ?></td><td class="text-end"><?= money($d['subtotal']) ?></td></tr>
<?php endforeach; ?></tbody></table>
<div class="text-end">
  <div>Subtotal: <strong><?= money($orden['subtotal']) ?></strong></div>
  <div>ITBMS: <strong><?= money($orden['itbms']) ?></strong></div>
  <div class="h5">Total: <?= money($orden['total']) ?></div>
</div></div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
