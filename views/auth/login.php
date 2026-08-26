<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-4">
    <div class="card p-4 mt-5">
      <h3 class="mb-3 text-center"><i class="bi bi-box-seam"></i> FerrePlus</h3>
      <p class="text-muted text-center small">Sistema de Inventario</p>
      <?php if ($m = flash('error')): ?><div class="alert alert-danger"><?= e($m) ?></div><?php endif; ?>
      <form method="post">
        <?= csrf_field() ?>
        <div class="mb-3"><label>Usuario</label><input class="form-control" name="username" required></div>
        <div class="mb-3"><label>Contraseña</label><input class="form-control" type="password" name="password" required></div>
        <button class="btn btn-primary w-100">Entrar</button>
      </form>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
