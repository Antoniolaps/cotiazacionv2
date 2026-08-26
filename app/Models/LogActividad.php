<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActividad extends Model
{
    use HasFactory;

    protected $table = 'log_actividades';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'rol_id',
        'accion',
        'tabla_afectada',
        'registro_id',
        'cambios_anteriores',
        'cambios_nuevos',
        'ip_address',
        'fecha',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }
}
