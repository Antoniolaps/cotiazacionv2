<?php
$role = Auth::role();
$menu = [
  'admin'    => [
    ['Dashboard','dashboard/index','bi-speedometer2'],
    ['Productos','productos/index','bi-box'],
    ['Categorías','categorias/index','bi-tags'],
    ['Proveedores','proveedores/index','bi-truck'],
    ['Clientes','clientes/index','bi-people'],
    ['Inventario','inventario/index','bi-clipboard-data'],
    ['Ventas (POS)','ventas/punto_venta','bi-cash-coin'],
    ['Cotizaciones','ventas/cotizaciones','bi-file-earmark-text'],
    ['Facturas','ventas/index','bi-receipt'],
    ['Órdenes de Compra','compras/index','bi-cart-plus'],
    ['Pagos','pagos/index','bi-credit-card'],
    ['Reportes','reportes/index','bi-bar-chart-fill'],
    ['Logs de Sistema','logs/index','bi-journal-text'],
    ['Usuarios','usuarios/index','bi-person-badge'],
    ['Roles','roles/index','bi-shield-lock'],
  ],
  'vendedor' => [
    ['Dashboard','dashboard/index','bi-speedometer2'],
    ['POS','ventas/punto_venta','bi-cash-coin'],
    ['Cotizaciones','ventas/cotizaciones','bi-file-earmark-text'],
    ['Facturas','ventas/index','bi-receipt'],
    ['Clientes','clientes/index','bi-people'],
    ['Pagos','pagos/index','bi-credit-card'],
    ['Productos','productos/index','bi-box'],
  ],
  'almacen'  => [
    ['Dashboard','dashboard/index','bi-speedometer2'],
    ['Productos','productos/index','bi-box'],
    ['Categorías','categorias/index','bi-tags'],
    ['Proveedores','proveedores/index','bi-truck'],
    ['Inventario','inventario/index','bi-clipboard-data'],
    ['Órdenes de Compra','compras/index','bi-cart-plus'],
  ],
  'cliente'  => [
    ['Mis Pedidos','ventas/mios','bi-receipt'],
  ],
];
$items = $menu[$role] ?? [];
?>
<aside class="sidebar bg-light border-end p-3" style="min-width:220px;min-height:calc(100vh - 56px)">
  <ul class="nav flex-column">
    <?php foreach ($items as $it): ?>
      <li class="nav-item">
        <a class="nav-link text-dark" href="<?= url('?r=' . $it[1]) ?>">
          <i class="bi <?= $it[2] ?>"></i> <?= e($it[0]) ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</aside>
