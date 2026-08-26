<?php $title = 'Acceso denegado'; require __DIR__ . '/header.php'; ?>
<div class="text-center py-5">
  <h1 class="display-4">403</h1>
  <p>No tienes permiso para acceder a esta sección.</p>
  <a href="<?= url('?r=') ?>" class="btn btn-primary">Volver</a>
</div>
<?php require __DIR__ . '/footer.php'; ?>
