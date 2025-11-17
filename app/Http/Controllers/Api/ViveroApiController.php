<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViveroApiController extends Controller
{
    /**
     * Devuelve una lista de viveros que pertenecen a un usuario específico.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getViverosByUser(User $user)
    {
        $authenticatedUser = Auth::user();

        // Un administrador puede ver los viveros de cualquier usuario.
        if ($authenticatedUser->role->nombre_rol === 'Admin') {
            return response()->json($user->viveros);
        }

        // Los usuarios no administradores solo pueden ver sus propios viveros.
        if ($authenticatedUser->id !== $user->id) {
            return response()->json(['message' => 'No autorizado para acceder a este recurso.'], 403);
        }

        // Retorna los viveros relacionados con el usuario en formato JSON.
        return response()->json($user->viveros);
    }
}
