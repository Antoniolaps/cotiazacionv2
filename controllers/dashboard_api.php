<?php
function stats() {
    Auth::requireLogin();
    header('Content-Type: application/json');

    $db = DB::conn();
    $role = Auth::role();
    $user = Auth::user();

    $data = [
        'role' => $role,
        'stats' => [],
        'recent' => [],
        'recent_title' => ''
    ];

    if ($role === 'admin') {
        $data['stats'] = [
            ['label' => 'Productos activos', 'value' => $db->query('SELECT COUNT(*) c FROM productos WHERE activo=1')->fetch()['c'], 'icon' => 'bi-box', 'color' => 'primary'],
            ['label' => 'Clientes activos', 'value' => $db->query('SELECT COUNT(*) c FROM clientes WHERE activo=1')->fetch()['c'], 'icon' => 'bi-people', 'color' => 'success'],
            ['label' => 'Ventas de hoy', 'value' => '$' . number_format($db->query("SELECT COALESCE(SUM(total),0) c FROM ventas WHERE DATE(fecha_venta)=CURDATE() AND estado<>'anulada'")->fetch()['c'], 2), 'icon' => 'bi-cash-coin', 'color' => 'warning'],
            ['label' => 'Bajo stock', 'value' => $db->query('SELECT COUNT(*) c FROM productos p JOIN inventario i ON i.producto_id=p.id WHERE i.stock_actual <= p.stock_minimo')->fetch()['c'], 'icon' => 'bi-exclamation-triangle', 'color' => 'danger'],
        ];
        $recientes = $db->query("SELECT v.*, c.nombre cliente FROM ventas v LEFT JOIN clientes c ON c.id=v.cliente_id ORDER BY v.fecha_venta DESC LIMIT 8")->fetchAll();
        $data['recent_title'] = 'Ventas recientes';
        
        foreach ($recientes as $v) {
            $data['recent'][] = [
                $v['numero_factura'],
                $v['cliente'] ?? '—',
                $v['fecha_venta'],
                $v['estado'],
                '$' . number_format($v['total'], 2)
            ];
        }
        $data['recent_headers'] = ['Factura', 'Cliente', 'Fecha', 'Estado', 'Total'];
        $data['quick_actions'] = [
            ['label' => 'Ver Reportes', 'url' => '?r=ventas/index', 'icon' => 'bi-bar-chart', 'color' => 'primary'],
            ['label' => 'Gestión de Usuarios', 'url' => '?r=usuarios/index', 'icon' => 'bi-people', 'color' => 'secondary']
        ];
        
        // Datos para Gráficos
        $data['charts'] = [];
        
        // Ventas últimos 7 días
        $ventas7 = $db->query("SELECT DATE(fecha_venta) d, SUM(total) t FROM ventas WHERE fecha_venta >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND estado<>'anulada' GROUP BY DATE(fecha_venta) ORDER BY d ASC")->fetchAll();
        $labels7 = []; $data7 = [];
        for ($i=6; $i>=0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels7[] = date('d/m', strtotime("-$i days"));
            $found = false;
            foreach($ventas7 as $v) { if($v['d'] == $date) { $data7[] = (float)$v['t']; $found=true; break; } }
            if(!$found) $data7[] = 0;
        }
        $data['charts']['ventas_7d'] = ['labels' => $labels7, 'data' => $data7];
        
        // Top 5 productos más vendidos (por cantidad)
        $top = $db->query("SELECT p.nombre, SUM(d.cantidad) c FROM detalle_ventas d JOIN ventas v ON v.id=d.venta_id JOIN productos p ON p.id=d.producto_id WHERE v.estado<>'anulada' GROUP BY p.id ORDER BY c DESC LIMIT 5")->fetchAll();
        $labelsTop = []; $dataTop = [];
        foreach($top as $t) {
            $labelsTop[] = $t['nombre'];
            $dataTop[] = (int)$t['c'];
        }
        $data['charts']['top_productos'] = ['labels' => $labelsTop, 'data' => $dataTop];
    } 
    elseif ($role === 'vendedor') {
        $data['stats'] = [
            ['label' => 'Mis Ventas de hoy', 'value' => '$' . number_format($db->query("SELECT COALESCE(SUM(total),0) c FROM ventas WHERE DATE(fecha_venta)=CURDATE() AND estado<>'anulada' AND vendedor_id=" . (int)$user['id'])->fetch()['c'], 2), 'icon' => 'bi-cash-coin', 'color' => 'success'],
            ['label' => 'Total mis ventas', 'value' => $db->query('SELECT COUNT(*) c FROM ventas WHERE vendedor_id=' . (int)$user['id'])->fetch()['c'], 'icon' => 'bi-receipt', 'color' => 'primary'],
            ['label' => 'Clientes registrados', 'value' => $db->query('SELECT COUNT(*) c FROM clientes WHERE activo=1')->fetch()['c'], 'icon' => 'bi-people', 'color' => 'info'],
        ];
        $recientes = $db->query("SELECT v.*, c.nombre cliente FROM ventas v LEFT JOIN clientes c ON c.id=v.cliente_id WHERE v.vendedor_id=" . (int)$user['id'] . " ORDER BY v.fecha_venta DESC LIMIT 8")->fetchAll();
        $data['recent_title'] = 'Mis ventas recientes';
        
        foreach ($recientes as $v) {
            $data['recent'][] = [
                $v['numero_factura'],
                $v['cliente'] ?? '—',
                $v['fecha_venta'],
                $v['estado'],
                '$' . number_format($v['total'], 2)
            ];
        }
        $data['recent_headers'] = ['Factura', 'Cliente', 'Fecha', 'Estado', 'Total'];
        $data['quick_actions'] = [
            ['label' => 'Registrar Venta (POS)', 'url' => '?r=ventas/punto_venta', 'icon' => 'bi-cash-coin', 'color' => 'success'],
            ['label' => 'Nuevo Cliente', 'url' => '?r=clientes/create', 'icon' => 'bi-person-plus', 'color' => 'primary']
        ];
    }
    elseif ($role === 'almacen') {
        $data['stats'] = [
            ['label' => 'Productos en stock', 'value' => $db->query('SELECT COUNT(*) c FROM productos WHERE activo=1')->fetch()['c'], 'icon' => 'bi-box', 'color' => 'primary'],
            ['label' => 'Bajo stock', 'value' => $db->query('SELECT COUNT(*) c FROM productos p JOIN inventario i ON i.producto_id=p.id WHERE i.stock_actual <= p.stock_minimo')->fetch()['c'], 'icon' => 'bi-exclamation-triangle', 'color' => 'danger'],
            ['label' => 'Compras este mes', 'value' => $db->query("SELECT COUNT(*) c FROM ordenes_compra WHERE MONTH(fecha_orden)=MONTH(CURDATE()) AND YEAR(fecha_orden)=YEAR(CURDATE())")->fetch()['c'], 'icon' => 'bi-cart-check', 'color' => 'success'],
            ['label' => 'Proveedores', 'value' => $db->query('SELECT COUNT(*) c FROM proveedores WHERE activo=1')->fetch()['c'], 'icon' => 'bi-shop', 'color' => 'info'],
        ];
        
        $recientes = $db->query("SELECT p.codigo, p.nombre, i.stock_actual, p.stock_minimo FROM productos p JOIN inventario i ON i.producto_id=p.id WHERE i.stock_actual <= p.stock_minimo ORDER BY i.stock_actual ASC LIMIT 8")->fetchAll();
        $data['recent_title'] = 'Productos con bajo stock';
        
        foreach ($recientes as $p) {
            $data['recent'][] = [
                $p['codigo'],
                $p['nombre'],
                $p['stock_actual'],
                $p['stock_minimo']
            ];
        }
        $data['recent_headers'] = ['Código', 'Producto', 'Stock Actual', 'Stock Mínimo'];
        $data['quick_actions'] = [
            ['label' => 'Nueva Orden de Compra', 'url' => '?r=compras/create', 'icon' => 'bi-cart-plus', 'color' => 'success'],
            ['label' => 'Ajuste de Inventario', 'url' => '?r=inventario/index', 'icon' => 'bi-clipboard-check', 'color' => 'primary']
        ];
    }
    elseif ($role === 'cliente') {
        // Encontrar al cliente por email
        $stmt = $db->prepare('SELECT id FROM clientes WHERE email=? LIMIT 1');
        $stmt->execute([$user['email']]);
        $cliente = $stmt->fetch();
        $cliente_id = $cliente ? (int)$cliente['id'] : 0;
        
        $data['stats'] = [
            ['label' => 'Mis pedidos totales', 'value' => $db->query("SELECT COUNT(*) c FROM ventas WHERE cliente_id={$cliente_id}")->fetch()['c'], 'icon' => 'bi-bag', 'color' => 'primary'],
            ['label' => 'Pedidos pendientes', 'value' => $db->query("SELECT COUNT(*) c FROM ventas WHERE estado='pendiente' AND cliente_id={$cliente_id}")->fetch()['c'], 'icon' => 'bi-clock', 'color' => 'warning'],
        ];
        
        $recientes = $db->query("SELECT * FROM ventas WHERE cliente_id={$cliente_id} ORDER BY fecha_venta DESC LIMIT 8")->fetchAll();
        $data['recent_title'] = 'Mis pedidos recientes';
        
        foreach ($recientes as $v) {
            $data['recent'][] = [
                $v['numero_factura'],
                $v['fecha_venta'],
                $v['estado'],
                '$' . number_format($v['total'], 2)
            ];
        }
        $data['recent_headers'] = ['Factura', 'Fecha', 'Estado', 'Total'];
        $data['quick_actions'] = [
            ['label' => 'Ver mis pedidos', 'url' => '?r=ventas/mios', 'icon' => 'bi-receipt', 'color' => 'primary']
        ];
    }

    echo json_encode($data);
    exit;
}
