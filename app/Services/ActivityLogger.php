<?php

namespace App\Services;

use App\Models\LogActividad;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Registra una acción realizada en el sistema.
     */
    public static function log(string $accion, string $tabla, ?int $registroId = null, $anteriores = null, $nuevos = null): void
    {
        $user = Auth::user();

        LogActividad::create([
            'usuario_id' => $user?->id,
            'rol_id' => $user?->rol_id,
            'accion' => $accion,
            'tabla_afectada' => $tabla,
            'registro_id' => $registroId,
            'cambios_anteriores' => $anteriores ? (is_string($anteriores) ? $anteriores : json_encode($anteriores, JSON_UNESCAPED_UNICODE)) : null,
            'cambios_nuevos' => $nuevos ? (is_string($nuevos) ? $nuevos : json_encode($nuevos, JSON_UNESCAPED_UNICODE)) : null,
            'ip_address' => request()->ip(),
            'fecha' => now(),
        ]);
    }
}
