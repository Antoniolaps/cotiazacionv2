<?php
/**
 * Controlador de Cotizaciones
 * Redirige y delega la lógica a la tabla `ventas` (donde estado = 'cotizacion')
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/ventas.php';

function index() {
    cotizaciones();
}

function create() {
    cotizacion_create();
}

function store() {
    cotizacion_store();
}

function api_guardar() {
    cotizacion_store();
}

function ver() {
    require_once __DIR__ . '/ventas.php';
    Auth::requireLogin();
    $id = (int)($_GET['id'] ?? 0);
    $c = DB::conn()->prepare("
        SELECT v.*, cl.nombre cliente, cl.cedula_ruc, cl.direccion, cl.telefono, cl.email, u.nombre vendedor
        FROM ventas v 
        LEFT JOIN clientes cl ON cl.id = v.cliente_id
        LEFT JOIN usuarios u ON u.id = v.vendedor_id 
        WHERE v.id = ? AND v.estado = 'cotizacion'
    ");
    $c->execute([$id]); 
    $venta = $c->fetch();
    
    if (!$venta) { 
        flash('error', 'Cotización no encontrada'); 
        redirect('?r=ventas/cotizaciones'); 
    }
    
    $d = DB::conn()->prepare("
        SELECT d.*, p.nombre, p.codigo 
        FROM detalle_ventas d 
        JOIN productos p ON p.id = d.producto_id 
        WHERE d.venta_id = ?
    ");
    $d->execute([$id]); 
    $detalle = $d->fetchAll();
    $pagos = [];
    $pagado = 0;
    
    $title = 'Cotización ' . $venta['numero_factura'];
    require __DIR__ . '/../views/ventas/ver.php';
}

function convertir() {
    convertir_cotizacion();
}
