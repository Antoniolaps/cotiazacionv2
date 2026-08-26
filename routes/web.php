<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\LogController;

/*
|--------------------------------------------------------------------------
| Web Routes - sistemSIU / FerrePlus
|--------------------------------------------------------------------------
*/

// Autenticación
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('api/ventas-chart', [DashboardController::class, 'apiVentasChart'])->name('api.ventas_chart');

    // Terminal POS (Roles: admin, vendedor)
    Route::middleware(['role:admin,vendedor'])->group(function () {
        Route::get('pos', [PosController::class, 'terminal'])->name('pos.terminal');
        Route::get('pos/productos', [PosController::class, 'apiProductos'])->name('pos.api_productos');
        Route::get('pos/stock', [PosController::class, 'apiStock'])->name('pos.api_stock');
        Route::post('pos/vender', [PosController::class, 'apiVender'])->name('pos.api_vender');
    });

    Route::get('pos/display', [PosController::class, 'customerDisplay'])->name('pos.display');

    // Cotizaciones (admin, vendedor, almacen)
    Route::middleware(['role:admin,vendedor,almacen'])->group(function () {
        Route::get('cotizaciones', [CotizacionController::class, 'index'])->name('cotizaciones.index');
        Route::get('cotizaciones/nueva', [CotizacionController::class, 'create'])->name('cotizaciones.create');
        Route::post('cotizaciones', [CotizacionController::class, 'store'])->name('cotizaciones.store');

        // Búsqueda Modal — UN solo request por acción manual (no real-time)
        Route::get('cotizaciones/buscar/cliente',   [CotizacionController::class, 'buscarCliente'])->name('cotizaciones.buscar.cliente');
        Route::get('cotizaciones/buscar/producto',  [CotizacionController::class, 'buscarProducto'])->name('cotizaciones.buscar.producto');
        Route::get('cotizaciones/buscar/proveedor', [CotizacionController::class, 'buscarProveedor'])->name('cotizaciones.buscar.proveedor');
        Route::post('cotizaciones/validar-lote',    [CotizacionController::class, 'validarLote'])->name('cotizaciones.validar_lote');

        Route::get('cotizaciones/{cotizacion}', [CotizacionController::class, 'show'])->name('cotizaciones.show');
        Route::post('cotizaciones/{cotizacion}/convertir', [CotizacionController::class, 'convertir'])->name('cotizaciones.convertir');
    });

    // Módulo Productos (admin, almacen, vendedor, cliente_consulta)
    Route::middleware(['role:admin,almacen,vendedor,cliente_consulta'])->group(function () {
        Route::resource('productos', ProductoController::class);
    });

    // Módulo Categorías, Inventario, Proveedores y Compras (admin, almacen)
    Route::middleware(['role:admin,almacen'])->group(function () {
        Route::resource('categorias', CategoriaController::class)->except(['create', 'show', 'edit']);
        Route::get('inventario', [InventarioController::class, 'index'])->name('inventario.index');
        Route::get('inventario/movimientos', [InventarioController::class, 'movimientos'])->name('inventario.movimientos');
        Route::post('inventario/ajustar', [InventarioController::class, 'ajustar'])->name('inventario.ajustar');
        Route::resource('proveedores', ProveedorController::class)->except(['create', 'show', 'edit']);
        Route::resource('compras', CompraController::class)->only(['index', 'show']);
    });

    // Módulo Ventas, Clientes y Pagos (admin, vendedor)
    Route::middleware(['role:admin,vendedor'])->group(function () {
        Route::resource('clientes', ClienteController::class)->except(['create', 'show', 'edit']);
        Route::resource('ventas', VentaController::class)->only(['index', 'show']);
        Route::post('ventas/{venta}/anular', [VentaController::class, 'anular'])->name('ventas.anular');
        Route::get('pagos', [PagoController::class, 'index'])->name('pagos.index');
    });

    // Módulo Administración (admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('usuarios', UsuarioController::class)->except(['create', 'show', 'edit']);
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('logs', [LogController::class, 'index'])->name('logs.index');
    });

});
