<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * NOTA:  Manejo de cors para permitir solicitudes de diferentes orígenes. se deja abierto para todos los orígenes.
     * POR QUESTION DE LA PRUEBA, ESTO NO ES CORRECTO EN PRODUCCIÓN.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Set CORS headers
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        return $response;
    }
}
