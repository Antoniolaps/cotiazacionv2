<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="d-flex justify-content-between mb-3"><h3>Facturas</h3>
  <a class="btn btn-primary" href="<?= url('?r=ventas/punto_venta') ?>"><i class="bi bi-cash-coin"></i> Nueva Venta (POS)</a></div>
<div class="card p-3"><table class="table table-hover">
<thead><tr><th>Factura</th><th>Fecha</th><th>Cliente</th><th>Vendedor</th><th>Estado</th><th class="text-end">Total</th><th></th></tr></thead>
<tbody><?php foreach ($rows as $v): ?>
<tr><td><strong><?= e($v['numero_factura']) ?></strong></td><td><?= e($v['fecha_venta']) ?></td>
<td><?= e($v['cliente'] ?? '—') ?></td><td><?= e($v['vendedor'] ?? '—') ?></td>
<td><span class="badge bg-<?= $v['estado']==='pagada'?'success':($v['estado']==='anulada'?'danger':'warning') ?>"><?= e($v['estado']) ?></span></td>
<td class="text-end"><?= money($v['total']) ?></td>
<td class="text-end"><a class="btn btn-sm btn-outline-primary" href="<?= url('?r=ventas/ver&id='.$v['id']) ?>"><i class="bi bi-eye"></i></a></td>
</tr><?php endforeach; ?></tbody></table></div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
