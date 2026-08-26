<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';
    public $timestamps = false;

    protected $fillable = [
        'venta_id',
        'monto',
        'moneda',
        'monto_recibido',
        'cambio',
        'fecha_pago',
        'metodo_pago',
        'estado',
        'codigo_autorizacion',
        'referencia',
        'terminal_id',
        'mensaje_respuesta',
        'usuario_id',
        'observaciones',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
