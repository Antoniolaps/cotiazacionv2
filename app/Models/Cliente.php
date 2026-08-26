<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'cedula_ruc',
        'tipo_cliente',
        'telefono',
        'email',
        'direccion',
        'descuento_porcentaje',
        'activo',
        'fecha_creacion',
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }
}
