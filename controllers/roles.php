<?php
require_once __DIR__ . '/../includes/crud.php';
$crud = new Crud([
  'table'   => 'roles',
  'module'  => 'roles',
  'title'   => 'Roles',
  'roles'   => ['admin'],
  'columns' => ['nombre'=>'Nombre','descripcion'=>'Descripción','activo'=>'Activo'],
  'fields'  => [
    'nombre'      => ['label'=>'Nombre','type'=>'text','required'=>true],
    'descripcion' => ['label'=>'Descripción','type'=>'text'],
    'permisos'    => ['label'=>'Permisos (texto libre)','type'=>'textarea'],
    'activo'      => ['label'=>'Activo','type'=>'checkbox','default'=>1],
  ],
]);
function index()  { global $crud; $crud->dispatch('index'); }
function create() { global $crud; $crud->dispatch('create'); }
function edit()   { global $crud; $crud->dispatch('edit'); }
function store()  { global $crud; $crud->dispatch('store'); }
function update() { global $crud; $crud->dispatch('update'); }
function delete() { global $crud; $crud->dispatch('delete'); }
