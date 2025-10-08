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
    
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // El operador '...' convierte los roles ('Admin', 'Dueño de Vivero') 
        // que pasamos en la ruta, en un array como ['Admin', 'Dueño de Vivero'].
        
        // Si el usuario no está logueado O si su rol no está en la lista de roles permitidos...
        if (!Auth::check() || !in_array(Auth::user()->role->nombre_rol, $roles)) {
            // ...entonces negamos el acceso.
            abort(403, 'Acceso no autorizado.');
        }

        // Si todo está bien, permitimos que la solicitud continúe.
        return $next($request);
    }
}
