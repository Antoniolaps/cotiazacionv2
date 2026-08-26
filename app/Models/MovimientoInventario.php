<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    use HasFactory;

    protected $table = 'movimientos_inventario';
    public $timestamps = false;

    protected $fillable = [
        'producto_id',
        'usuario_id',
        'tipo_movimiento',
        'cantidad',
        'venta_id',
        'orden_compra_id',
        'fecha_movimiento',
        'descripcion',
        'stock_anterior',
        'stock_nuevo',
        'observaciones',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    public function ordenCompra()
    {
        return $this->belongsTo(OrdenCompra::class, 'orden_compra_id');
    }
}
