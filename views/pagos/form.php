<?php require __DIR__ . '/../layouts/header.php'; ?>
<h3>Registrar pago — Factura <?= e($venta['numero_factura']) ?></h3>
<p>Total: <strong><?= money($venta['total']) ?></strong> · Pagado: <strong><?= money($venta['pagado']) ?></strong> · Saldo: <strong class="text-danger"><?= money($saldo) ?></strong></p>
<div class="card p-4" style="max-width:560px">
<form method="post" action="<?= url('?r=pagos/store') ?>">
  <?= csrf_field() ?><input type="hidden" name="venta_id" value="<?= e($venta['id']) ?>">
  <div class="mb-3"><label>Monto</label><input type="number" step="0.01" min="0.01" max="<?= e($saldo) ?>" class="form-control" name="monto" value="<?= e($saldo) ?>" required></div>
  <div class="mb-3"><label>Método</label>
    <select class="form-select" name="metodo_pago" required>
      <option value="efectivo">Efectivo</option>
      <option value="tarjeta_credito">Tarjeta de Crédito</option>
      <option value="tarjeta_debito">Tarjeta de Débito</option>
      <option value="transferencia">Transferencia</option>
      <option value="yappy">Yappy</option>
      <option value="nequi">Nequi</option>
      <option value="vale">Vale / Gift Card</option>
      <option value="cheque">Cheque</option>
      <option value="deposito">Depósito</option>
    </select></div>
  <div class="mb-3"><label>Referencia</label><input class="form-control" name="referencia"></div>
  <div class="mb-3"><label>Observaciones</label><textarea class="form-control" name="observaciones"></textarea></div>
  <button class="btn btn-primary"><i class="bi bi-check2-circle"></i> Confirmar pago</button>
  <a class="btn btn-outline-secondary" href="<?= url('?r=ventas/ver&id='.$venta['id']) ?>">Cancelar</a>
</form></div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
