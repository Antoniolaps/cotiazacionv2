<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictLocalNetwork
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        $isLocal = ($ip === '127.0.0.1' || $ip === '::1' ||
                    str_starts_with($ip, '192.168.') ||
                    str_starts_with($ip, '10.') ||
                    preg_match('/^172\.(1[6-9]|2[0-9]|3[0-1])\./', $ip));

        if (!$isLocal) {
            abort(403, '403 Forbidden - Acceso restringido a la red local.');
        }

        if (config('app.env') === 'production' && !$request->secure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
