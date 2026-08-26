<?php require __DIR__ . '/../layouts/header.php'; ?>
<h3>Pagos recibidos</h3>
<div class="card p-3"><table class="table"><thead>
<tr><th>Fecha</th><th>Factura</th><th>Cliente</th><th>Método</th><th>Referencia</th><th class="text-end">Monto</th></tr></thead>
<tbody><?php foreach ($rows as $p): ?>
<tr><td><?= e($p['fecha_pago']) ?></td><td><?= e($p['numero_factura']) ?></td><td><?= e($p['cliente'] ?? '—') ?></td>
<td><span class="badge bg-info"><?= e($p['metodo_pago']) ?></span></td><td><?= e($p['referencia']) ?></td>
<td class="text-end"><strong><?= money($p['monto']) ?></strong></td></tr>
<?php endforeach; ?></tbody></table></div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
