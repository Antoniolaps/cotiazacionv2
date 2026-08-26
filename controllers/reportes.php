<?php
/**
 * Controlador de Reportes
 * Genera 4 tipos de reportes descargables en CSV
 */

function index() {
    Auth::requireRole(['admin']);
    $vendedores = DB::conn()->query("SELECT id, nombre FROM usuarios WHERE estado=1 ORDER BY nombre")->fetchAll();
    $proveedores = DB::conn()->query("SELECT id, nombre FROM proveedores WHERE activo=1 ORDER BY nombre")->fetchAll();
    $title = 'Reportes';
    require __DIR__ . '/../views/reportes/index.php';
}

function ventas_periodo() {
    Auth::requireRole(['admin']);
    $desde    = $_GET['desde'] ?? date('Y-m-01');
    $hasta    = $_GET['hasta'] ?? date('Y-m-d');
    $vendedor = (int)($_GET['vendedor_id'] ?? 0);

    $sql = "SELECT v.numero_factura, DATE(v.fecha_venta) fecha, 
                   c.nombre cliente, u.nombre vendedor,
                   v.subtotal, v.itbms, v.total, v.estado
            FROM ventas v
            LEFT JOIN clientes c ON c.id = v.cliente_id
            LEFT JOIN usuarios u ON u.id = v.vendedor_id
            WHERE DATE(v.fecha_venta) BETWEEN ? AND ?
              AND v.estado <> 'anulada'";
    $params = [$desde, $hasta];
    if ($vendedor) { $sql .= " AND v.vendedor_id = ?"; $params[] = $vendedor; }
    $sql .= " ORDER BY v.fecha_venta DESC";

    $rows = DB::conn()->prepare($sql);
    $rows->execute($params);
    $data = $rows->fetchAll();

    $headers = ['Factura','Fecha','Cliente','Vendedor','Subtotal','ITBMS','Total','Estado'];
    exportCsv("ventas_{$desde}_{$hasta}.csv", $headers, $data, ['numero_factura','fecha','cliente','vendedor','subtotal','itbms','total','estado']);
}

function inventario_actual() {
    Auth::requireRole(['admin']);
    $data = DB::conn()->query(
        "SELECT p.codigo, p.nombre, 
                c.nombre categoria, pr.nombre proveedor,
                COALESCE(i.stock_actual,0) stock_actual,
                p.stock_minimo,
                p.precio_compra, p.precio_venta,
                p.unidad_medida,
                i.ultima_actualizacion
         FROM productos p
         LEFT JOIN categoria c ON c.id = p.categoria_id
         LEFT JOIN proveedores pr ON pr.id = p.proveedor_id
         LEFT JOIN inventario i ON i.producto_id = p.id
         WHERE p.activo = 1
         ORDER BY p.nombre"
    )->fetchAll();

    $headers = ['Código','Nombre','Categoría','Proveedor','Stock Actual','Stock Mínimo','Precio Compra','Precio Venta','Unidad','Última Actualización'];
    exportCsv('inventario_actual_'.date('Y-m-d').'.csv', $headers, $data, ['codigo','nombre','categoria','proveedor','stock_actual','stock_minimo','precio_compra','precio_venta','unidad_medida','ultima_actualizacion']);
}

function compras_periodo() {
    Auth::requireRole(['admin']);
    $desde = $_GET['desde'] ?? date('Y-m-01');
    $hasta = $_GET['hasta'] ?? date('Y-m-d');

    $data = DB::conn()->prepare(
        "SELECT oc.numero_orden, oc.numero_factura, DATE(oc.fecha_orden) fecha,
                pr.nombre proveedor, u.nombre usuario,
                oc.subtotal, oc.itbms, oc.total, oc.estado
         FROM ordenes_compra oc
         JOIN proveedores pr ON pr.id = oc.proveedor_id
         LEFT JOIN usuarios u ON u.id = oc.usuario_id
         WHERE DATE(oc.fecha_orden) BETWEEN ? AND ?
         ORDER BY oc.fecha_orden DESC"
    );
    $data->execute([$desde, $hasta]);
    $rows = $data->fetchAll();

    $headers = ['Nº Orden','Nº Factura','Fecha','Proveedor','Usuario','Subtotal','ITBMS','Total','Estado'];
    exportCsv("compras_{$desde}_{$hasta}.csv", $headers, $rows, ['numero_orden','numero_factura','fecha','proveedor','usuario','subtotal','itbms','total','estado']);
}

function top_productos() {
    Auth::requireRole(['admin']);
    $desde = $_GET['desde'] ?? date('Y-m-01');
    $hasta = $_GET['hasta'] ?? date('Y-m-d');

    $data = DB::conn()->prepare(
        "SELECT p.codigo, p.nombre, 
                SUM(d.cantidad) cantidad_vendida,
                SUM(d.subtotal) monto_total,
                p.precio_venta,
                COALESCE(i.stock_actual,0) stock_actual
         FROM detalle_ventas d
         JOIN ventas v ON v.id = d.venta_id
         JOIN productos p ON p.id = d.producto_id
         LEFT JOIN inventario i ON i.producto_id = p.id
         WHERE DATE(v.fecha_venta) BETWEEN ? AND ?
           AND v.estado <> 'anulada'
         GROUP BY p.id
         ORDER BY cantidad_vendida DESC
         LIMIT 100"
    );
    $data->execute([$desde, $hasta]);
    $rows = $data->fetchAll();

    $headers = ['Código','Producto','Cantidad Vendida','Monto Total','Precio Venta','Stock Actual'];
    exportCsv("top_productos_{$desde}_{$hasta}.csv", $headers, $rows, ['codigo','nombre','cantidad_vendida','monto_total','precio_venta','stock_actual']);
}

function cotizaciones_periodo() {
    Auth::requireRole(['admin']);
    $desde = $_GET['desde'] ?? date('Y-m-01');
    $hasta = $_GET['hasta'] ?? date('Y-m-d');

    $sql = "SELECT v.numero_factura, DATE(v.fecha_venta) fecha,
                   c.nombre cliente, u.nombre vendedor,
                   v.subtotal, v.itbms, v.total, v.estado
            FROM ventas v
            LEFT JOIN clientes c ON c.id = v.cliente_id
            LEFT JOIN usuarios u ON u.id = v.vendedor_id
            WHERE DATE(v.fecha_venta) BETWEEN ? AND ?
              AND v.estado = 'cotizacion'
            ORDER BY v.fecha_venta DESC";

    $stmt = DB::conn()->prepare($sql);
    $stmt->execute([$desde, $hasta]);
    $data = $stmt->fetchAll();

    $headers = ['Nº Cotización','Fecha','Cliente','Vendedor','Subtotal','ITBMS','Total','Estado'];
    exportCsv("cotizaciones_{$desde}_{$hasta}.csv", $headers, $data, ['numero_factura','fecha','cliente','vendedor','subtotal','itbms','total','estado']);
}

function logs_actividades() {
    Auth::requireRole(['admin']);
    $desde = $_GET['desde'] ?? date('Y-m-01');
    $hasta = $_GET['hasta'] ?? date('Y-m-d');

    $sql = "SELECT l.fecha, u.nombre usuario, r.nombre rol, l.accion, l.tabla_afectada, l.registro_id, l.ip_address
            FROM log_actividades l
            LEFT JOIN usuarios u ON u.id = l.usuario_id
            LEFT JOIN roles r ON r.id = l.rol_id
            WHERE DATE(l.fecha) BETWEEN ? AND ?
            ORDER BY l.fecha DESC";

    $stmt = DB::conn()->prepare($sql);
    $stmt->execute([$desde, $hasta]);
    $data = $stmt->fetchAll();

    $headers = ['Fecha / Hora','Usuario','Rol','Acción','Tabla Afectada','ID Registro','IP'];
    exportCsv("logs_{$desde}_{$hasta}.csv", $headers, $data, ['fecha','usuario','rol','accion','tabla_afectada','registro_id','ip_address']);
}

/* ─── Helper CSV ─── */
function exportCsv(string $filename, array $headers, array $rows, array $cols): void {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    $out = fopen('php://output', 'w');
    // BOM para Excel
    fputs($out, "\xEF\xBB\xBF");
    fputcsv($out, $headers, ';');
    foreach ($rows as $row) {
        $line = [];
        foreach ($cols as $col) {
            $line[] = $row[$col] ?? '';
        }
        fputcsv($out, $line, ';');
    }
    fclose($out);
    exit;
}
