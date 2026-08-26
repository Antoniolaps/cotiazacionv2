<?php require __DIR__ . '/../layouts/header.php'; ?>
<h3>Ajuste de inventario — <?= e($producto['nombre']) ?></h3>
<p>Stock actual: <strong><?= e($producto['stock']) ?></strong></p>
<div class="card p-4" style="max-width:640px">
<form method="post" action="<?= url('?r=inventario/aplicar_ajuste') ?>">
  <?= csrf_field() ?><input type="hidden" name="producto_id" value="<?= e($producto['id']) ?>">
  <div class="mb-3"><label>Tipo de movimiento</label>
    <select name="tipo" class="form-select" required>
      <option value="entrada">Entrada (sumar)</option>
      <option value="salida">Salida (restar)</option>
      <option value="ajuste">Ajuste (fijar valor exacto)</option>
      <option value="devolucion">Devolución</option>
    </select></div>
  <div class="mb-3"><label>Cantidad</label><input type="number" min="0" class="form-control" name="cantidad" required></div>
  <div class="mb-3"><label>Observaciones</label><textarea class="form-control" name="observaciones"></textarea></div>
  <button class="btn btn-primary"><i class="bi bi-save"></i> Aplicar</button>
  <a class="btn btn-outline-secondary" href="<?= url('?r=inventario/index') ?>">Cancelar</a>
</form></div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
