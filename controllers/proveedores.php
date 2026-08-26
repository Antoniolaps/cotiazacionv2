<?php
require_once __DIR__ . '/../includes/crud.php';
$cats = DB::conn()->query("SELECT id AS v, nombre AS l FROM categoria WHERE activo=1 ORDER BY nombre")->fetchAll();
$crud = new Crud([
  'table'  => 'proveedores',
  'module' => 'proveedores',
  'title'  => 'Proveedores',
  'roles'  => ['admin','almacen'],
  'columns'=> ['nombre'=>'Nombre','ruc'=>'RUC','tipo_proveedor'=>'Tipo','telefono'=>'Teléfono','email'=>'Email'],
  'fields' => [
    'nombre'             => ['label'=>'Nombre','type'=>'text','required'=>true],
    'ruc'                => ['label'=>'RUC','type'=>'text'],
    'categoria_id'       => ['label'=>'Categoría','type'=>'select','options'=>$cats],
    'tipo_proveedor'     => ['label'=>'Tipo','type'=>'select','options'=>['distribuidor','fabricante','importador','mayorista','otro']],
    'contacto'           => ['label'=>'Contacto','type'=>'text'],
    'telefono'           => ['label'=>'Teléfono','type'=>'text'],
    'email'              => ['label'=>'Email','type'=>'email'],
    'sitio_web'          => ['label'=>'Sitio web','type'=>'text'],
    'tiempo_entrega_dias'=> ['label'=>'Tiempo de entrega (días)','type'=>'number','default'=>0],
    'direccion'          => ['label'=>'Dirección','type'=>'textarea'],
    'descripcion'        => ['label'=>'Descripción','type'=>'textarea'],
    'activo'             => ['label'=>'Activo','type'=>'checkbox','default'=>1],
  ],
]);
function index()  { global $crud; $crud->dispatch('index'); }
function create() { global $crud; $crud->dispatch('create'); }
function edit()   { global $crud; $crud->dispatch('edit'); }
function store()  { global $crud; $crud->dispatch('store'); }
function update() { global $crud; $crud->dispatch('update'); }
function delete() { global $crud; $crud->dispatch('delete'); }
