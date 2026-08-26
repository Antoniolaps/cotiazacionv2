<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'ordenes_compra';
    public $timestamps = false;

    protected $fillable = [
        'numero_orden',
        'numero_factura',
        'proveedor_id',
        'usuario_id',
        'fecha_orden',
        'fecha_entrega_esperada',
        'estado',
        'subtotal',
        'itbms',
        'total',
        'observaciones',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleOrdenCompra::class, 'orden_compra_id');
    }
}
