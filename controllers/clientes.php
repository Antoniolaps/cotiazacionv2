<?php
require_once __DIR__ . '/../includes/crud.php';
$crud = new Crud([
  'table'  => 'clientes',
  'module' => 'clientes',
  'title'  => 'Clientes',
  'roles'  => ['admin','vendedor'],
  'columns'=> ['codigo'=>'Código','nombre'=>'Nombre','cedula_ruc'=>'Cédula/RUC','tipo_cliente'=>'Tipo','telefono'=>'Tel','email'=>'Email'],
  'fields' => [
    'codigo'              => ['label'=>'Código','type'=>'text','required'=>true],
    'nombre'              => ['label'=>'Nombre','type'=>'text','required'=>true],
    'cedula_ruc'          => ['label'=>'Cédula / RUC','type'=>'text'],
    'tipo_cliente'        => ['label'=>'Tipo','type'=>'select','options'=>['regular','mayorista','corporativo']],
    'telefono'            => ['label'=>'Teléfono','type'=>'text'],
    'email'               => ['label'=>'Email','type'=>'email'],
    'direccion'           => ['label'=>'Dirección','type'=>'textarea'],
    'descuento_porcentaje'=> ['label'=>'Descuento %','type'=>'number','default'=>0,'step'=>'0.01'],
    'activo'              => ['label'=>'Activo','type'=>'checkbox','default'=>1],
  ],
]);
function index()  { global $crud; $crud->dispatch('index'); }
function create() { global $crud; $crud->dispatch('create'); }
function edit()   { global $crud; $crud->dispatch('edit'); }
function store()  { global $crud; $crud->dispatch('store'); }
function update() { global $crud; $crud->dispatch('update'); }
function delete() { global $crud; $crud->dispatch('delete'); }
