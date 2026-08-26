<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="d-flex justify-content-between mb-3"><h3>Órdenes de compra</h3>
  <a class="btn btn-primary" href="<?= url('?r=compras/create') ?>"><i class="bi bi-plus-lg"></i> Nueva orden</a></div>
<div class="card p-3"><table class="table table-hover">
<thead><tr><th>Nº Orden</th><th>Nº Factura</th><th>Proveedor</th><th>Fecha</th><th>Estado</th><th class="text-end">Total</th><th></th></tr></thead>
<tbody><?php foreach ($rows as $o): ?>
<tr>
  <td><strong><?= e($o['numero_orden']) ?></strong></td>
  <td><?= e($o['numero_factura'] ?? '—') ?></td>
  <td><?= e($o['proveedor']) ?></td>
  <td><?= e(substr($o['fecha_orden'], 0, 10)) ?></td>
  <td><span class="badge bg-<?= $o['estado']==='recibida'?'success':($o['estado']==='cancelada'?'danger':'warning') ?>"><?= e($o['estado']) ?></span></td>
  <td class="text-end"><?= money($o['total']) ?></td>
  <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="<?= url('?r=compras/ver&id='.$o['id']) ?>"><i class="bi bi-eye"></i></a></td>
</tr><?php endforeach; ?></tbody></table></div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
