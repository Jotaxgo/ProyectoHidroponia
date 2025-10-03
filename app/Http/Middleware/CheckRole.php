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
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Verifica si el usuario está logueado Y si su rol coincide con el requerido
        if (!Auth::check() || Auth::user()->role->nombre_rol !== $role) {
            // Si no tiene el rol, lo redirigimos al dashboard normal
            abort(403, 'Acceso no autorizado.');
        }

        return $next($request);
    }
}
