<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'ruc',
        'categoria_id',
        'tipo_proveedor',
        'sitio_web',
        'tiempo_entrega_dias',
        'contacto',
        'telefono',
        'email',
        'direccion',
        'activo',
        'fecha_creacion',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'proveedor_id');
    }
}
