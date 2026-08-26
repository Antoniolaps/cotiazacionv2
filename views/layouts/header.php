<?php $user = Auth::user(); ?><!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? cfg('app_name')) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
  body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; margin: 0; }
  .crm-navbar { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 0 20px; display: flex; align-items: center; justify-content: space-between; height: 60px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
  .crm-nav-links { display: flex; height: 100%; align-items: center; overflow-x: auto; scrollbar-width: none; }
  .crm-nav-links::-webkit-scrollbar { display: none; }
  .crm-nav-link { text-decoration: none; color: #4a5568; font-size: 0.875rem; font-weight: 500; padding: 0 16px; display: flex; align-items: center; height: 100%; border-bottom: 3px solid transparent; white-space: nowrap; transition: color 0.2s; }
  .crm-nav-link:hover:not(.disabled) { color: #3182ce; }
  .crm-nav-link.active { color: #3182ce; border-bottom-color: #3182ce; }
  .crm-nav-link.disabled { color: #cbd5e0; cursor: not-allowed; }
  .crm-nav-brand { font-weight: 700; color: #2d3748; font-size: 1.1rem; display: flex; align-items: center; margin-right: 20px; text-decoration: none; }
  .crm-nav-brand i { color: #3182ce; margin-right: 8px; font-size: 1.3rem; }
  .crm-nav-right { display: flex; align-items: center; gap: 15px; }
  .crm-icon-btn { color: #718096; background: none; border: none; font-size: 1.1rem; cursor: pointer; transition: color 0.2s; text-decoration: none; }
  .crm-icon-btn:hover { color: #2d3748; }
  .user-badge { font-size: 0.75rem; background: #ebf8ff; color: #3182ce; padding: 2px 8px; border-radius: 12px; font-weight: 600; margin-left: 8px; }
</style>
</head>
<body>
<?php if ($user): 
  $role = $user['rol_nombre'] ?? Auth::role();
  $all_modules = [
    ['Dashboard', 'dashboard/index', ['administrador','vendedor','almacen']],
    ['POS', 'ventas/punto_venta', ['administrador','vendedor']],
    ['Productos', 'productos/index', ['administrador','vendedor','almacen']],
    ['Categorías', 'categorias/index', ['administrador','almacen']],
    ['Inventario', 'inventario/index', ['administrador','almacen']],
    ['Pagos', 'pagos/index', ['administrador','vendedor']],
    ['Cotizaciones', 'ventas/cotizaciones', ['administrador','vendedor','cliente']],
    ['Clientes', 'clientes/index', ['administrador','vendedor']],
    ['Proveedores', 'proveedores/index', ['administrador','almacen']],
    ['Compras', 'compras/index', ['administrador','almacen']],
    ['Ventas', 'ventas/index', ['administrador']],
    ['Reportes', 'reportes/index', ['administrador']],
    ['Logs', 'logs/index', ['administrador']],
    ['Usuarios', 'usuarios/index', ['administrador']],
    ['Roles', 'roles/index', ['administrador']]
  ];
  $current_route = $_GET['r'] ?? 'dashboard/index';
?>
<div class="crm-navbar">
  <div class="d-flex align-items-center" style="flex: 1; overflow: hidden;">
    <a class="crm-nav-brand" href="<?= url('?r=dashboard/index') ?>">
      <i class="bi bi-box-seam"></i> FerrePlus
    </a>
    <div class="crm-nav-links">
      <?php foreach ($all_modules as $mod): 
        $has_access = in_array($role, $mod[2]);
        $is_active = (strpos($current_route, explode('/', $mod[1])[0]) === 0);
      ?>
        <a href="<?= $has_access ? url('?r=' . $mod[1]) : '#' ?>" 
           class="crm-nav-link <?= $is_active ? 'active' : '' ?> <?= !$has_access ? 'disabled' : '' ?>"
           <?= !$has_access ? 'title="No tienes permiso para ver este módulo"' : '' ?>
           onclick="<?= !$has_access ? 'return false;' : '' ?>">
           <?= e($mod[0]) ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="crm-nav-right">
    
  
    <div class="d-flex align-items-center ms-3 ps-3 border-start">
      <span class="text-dark fw-medium" style="font-size: 0.875rem;">
        <?= e($user['nombre']) ?>
      </span>
      <span class="user-badge"><?= e($role) ?></span>
      <a href="<?= url('?r=auth/logout') ?>" class="crm-icon-btn ms-3 text-danger" title="Cerrar Sesión"><i class="bi bi-box-arrow-right"></i></a>
    </div>
  </div>
</div>

<div class="d-flex flex-column" style="min-height: calc(100vh - 60px);">
  <main class="flex-grow-1 p-4 w-100 mx-auto" style="max-width: 1400px;">
    <?php if ($m = flash('success')): ?><div class="alert alert-success shadow-sm"><?= e($m) ?></div><?php endif; ?>
    <?php if ($m = flash('error')): ?><div class="alert alert-danger shadow-sm"><?= e($m) ?></div><?php endif; ?>
<?php else: ?>
<main class="container py-5">
<?php endif; ?>
