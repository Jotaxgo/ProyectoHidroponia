<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
        // Retorna los viveros relacionados con el usuario en formato JSON.
        // El modelo User debe tener una relación hasMany('viveros') definida.
        return response()->json($user->viveros);
    }
}
