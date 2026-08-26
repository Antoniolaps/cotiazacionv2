<?php
require_once __DIR__ . '/../includes/crud.php';
$cats  = DB::conn()->query("SELECT id AS v, nombre AS l FROM categoria WHERE activo=1 ORDER BY nombre")->fetchAll();
$provs = DB::conn()->query("SELECT id AS v, nombre AS l FROM proveedores WHERE activo=1 ORDER BY nombre")->fetchAll();
$crud = new Crud([
  'table'  => 'productos',
  'module' => 'productos',
  'title'  => 'Productos',
  'roles'  => ['admin','almacen','vendedor'],
  'columns'=> ['codigo'=>'Código','nombre'=>'Nombre','precio_venta'=>'Precio','stock_minimo'=>'Stock mín','activo'=>'Activo'],
  'fields' => [
    'codigo'        => ['label'=>'Código-ref','type'=>'text','required'=>true],
    'codigo_barras' => ['label'=>'Código de barras','type'=>'text'],
    'nombre'        => ['label'=>'Nombre','type'=>'text','required'=>true],
    'categoria_id'  => ['label'=>'Categoría','type'=>'select','options'=>$cats],
    'proveedor_id'  => ['label'=>'Proveedor','type'=>'select','options'=>$provs],
    'precio_compra' => ['label'=>'Precio compra','type'=>'number','step'=>'0.01','required'=>true],
    'precio_venta'  => ['label'=>'Precio venta','type'=>'number','step'=>'0.01','required'=>true],
    'stock_minimo'  => ['label'=>'Stock mínimo','type'=>'number','default'=>0],
    'unidad_medida' => ['label'=>'Unidad','type'=>'text','default'=>'unidad'],
    'descripcion'   => ['label'=>'Descripción','type'=>'textarea'],
    'activo'        => ['label'=>'Activo','type'=>'checkbox','default'=>1],
  ],
]);

function index() {
    global $crud;
    Auth::requireRole($crud->roles);
    $q = trim((string)($_GET['q'] ?? ''));
    $page = max(1, (int)($_GET['p'] ?? 1));
    $limit = max(1, (int)($_GET['limit'] ?? 10));

    // Count query
    $sqlCount = "SELECT COUNT(*) FROM productos p";
    $params = [];
    if ($q !== '') {
        $sqlCount .= " WHERE p.nombre LIKE ? OR p.codigo LIKE ?"; 
        $params[] = "%$q%";
        $params[] = "%$q%";
    }
    
    $stmtCount = DB::conn()->prepare($sqlCount);
    $stmtCount->execute($params);
    $totalRows = (int)$stmtCount->fetchColumn();
    $totalPages = max(1, ceil($totalRows / $limit));
    
    if ($page > $totalPages) $page = $totalPages;
    $offset = ($page - 1) * $limit;

    $sql = "SELECT p.*, c.nombre as categoria_nombre, pr.nombre as proveedor_nombre, i.stock_actual
            FROM productos p
            LEFT JOIN categoria c ON p.categoria_id = c.id
            LEFT JOIN proveedores pr ON p.proveedor_id = pr.id
            LEFT JOIN inventario i ON p.id = i.producto_id";
            
    if ($q !== '') {
        $sql .= " WHERE p.nombre LIKE ? OR p.codigo LIKE ?"; 
    }
    
    $sql .= " ORDER BY p.id DESC LIMIT $limit OFFSET $offset";
    
    $stmt = DB::conn()->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();
    
    $title = "Productos";
    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/productos/index.php';
    require __DIR__ . '/../views/layouts/footer.php';
}
function show()   { global $crud; $crud->dispatch('show'); }
function create() { 
    Auth::requireRole(['admin']);
    global $crud; $crud->dispatch('create'); 
}
function edit()   { 
    Auth::requireRole(['admin']);
    global $crud; $crud->dispatch('edit'); 
}
function update() { 
    Auth::requireRole(['admin']);
    global $crud; $crud->dispatch('update'); 
}
function store()  { 
    Auth::requireRole(['admin']);
    global $crud; $crud->dispatch('store'); 
}
function delete() { 
    Auth::requireRole(['admin']);
    global $crud; $crud->dispatch('delete'); 
}
