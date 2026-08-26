<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';
    public $timestamps = false;

    protected $fillable = [
        'numero_factura',
        'cliente_id',
        'cliente_nombre',
        'vendedor_id',
        'fecha_venta',
        'subtotal',
        'descuento_total',
        'itbms',
        'total',
        'estado',
        'observaciones',
        'tipo_consumidor',
        'punto_facturacion',
        'dv',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'venta_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'venta_id');
    }
}
