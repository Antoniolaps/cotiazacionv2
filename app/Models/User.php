<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'username',
        'email',
        'password_hash',
        'rol_id',
        'telefono',
        'estado',
        'fecha_creacion',
        'ultimo_login',
    ];

    protected $hidden = [
        'password_hash',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'vendedor_id');
    }
}
