<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespaldoBackup extends Model
{
    use HasFactory;

    protected $table = 'respaldos_backup';
    public $timestamps = false;

    protected $fillable = [
        'nombre_archivo',
        'ruta',
        'tamano_bytes',
        'usuario_id',
        'fecha',
        'tipo_respaldo',
        'tablas_incluidas',
        'periodo_retencion_dias',
        'fecha_expiracion',
        'observaciones',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
