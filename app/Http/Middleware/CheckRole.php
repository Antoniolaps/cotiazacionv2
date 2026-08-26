<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = strtolower($user->role->nombre ?? 'invitado');

        // El rol admin siempre tiene acceso total
        if ($userRole === 'admin') {
            return $next($request);
        }

        $allowedRoles = array_map('strtolower', $roles);

        if (!in_array($userRole, $allowedRoles, true)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No tienes permisos para esta acción.'], 403);
            }
            abort(403, 'Acceso denegado: No cuentas con el rol requerido (' . implode(', ', $roles) . ').');
        }

        return $next($request);
    }
}
