<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="d-flex justify-content-between mb-3">
  <h3>Inventario</h3>
  <div class="search-prod">
    <input type="text" class="form-control" placeholder="Buscar producto" id="searchProd">
    <script>
      const searchProd = document.getElementById('searchProd');
      searchProd.addEventListener('keyup', (e) => {
        const searchValue = searchProd.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          if (text.includes(searchValue)) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      });
    </script>
  </div>
  <a class="btn btn-outline-secondary" href="<?= url('?r=inventario/movimientos') ?>"><i class="bi bi-clock-history"></i> Movimientos</a>
</div>
<div class="card p-3"><table class="table table-hover">
<thead><tr><th>Código</th><th>Producto</th><th class="text-end">Stock</th><th class="text-end">Reservado</th><th class="text-end">Mínimo</th><th>Actualizado</th><th></th></tr></thead>
<tbody><?php foreach ($rows as $r):
  $low = $r['stock_actual'] <= $r['stock_minimo']; ?>
<tr class="<?= $low ? 'table-warning' : '' ?>">
  <td><?= e($r['codigo']) ?></td><td><?= e($r['nombre']) ?></td>
  <td class="text-end"><strong><?= e($r['stock_actual']) ?></strong></td>
  <td class="text-end"><?= e($r['stock_reservado']) ?></td>
  <td class="text-end"><?= e($r['stock_minimo']) ?></td>
  <td><?= e($r['ultima_actualizacion'] ?? '—') ?></td>
  <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="<?= url('?r=inventario/ajustar&id='.$r['id']) ?>"><i class="bi bi-sliders"></i> Ajustar</a></td>
</tr>
<?php endforeach; ?></tbody></table></div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
