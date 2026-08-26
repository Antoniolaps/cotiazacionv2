<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="d-flex justify-content-between mb-3">
  <h3>Cotizaciones</h3>
  <a class="btn btn-primary" href="<?= url('?r=ventas/cotizacion_create') ?>"><i class="bi bi-file-earmark-plus"></i> Nueva Cotización</a>
</div>
<div class="card p-3">
  <table class="table table-hover align-middle">
    <thead>
      <tr>
        <th>Cotización</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Vendedor</th>
        <th>Estado</th>
        <th class="text-end">Total</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $v): ?>
      <tr>
        <td><strong><?= e($v['numero_factura']) ?></strong></td>
        <td><?= e(date('d/m/Y H:i', strtotime($v['fecha_venta']))) ?></td>
        <td><?= e($v['cliente'] ?? '—') ?></td>
        <td><?= e($v['vendedor'] ?? '—') ?></td>
        <td><span class="badge bg-info"><?= e(ucfirst($v['estado'])) ?></span></td>
        <td class="text-end fw-bold"><?= money($v['total']) ?></td>
        <td class="text-end">
          <a class="btn btn-sm btn-outline-primary" href="<?= url('?r=ventas/ver&id='.$v['id']) ?>"><i class="bi bi-eye"></i></a>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($rows)): ?>
      <tr>
        <td colspan="7" class="text-center py-4 text-muted">No hay cotizaciones registradas.</td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
