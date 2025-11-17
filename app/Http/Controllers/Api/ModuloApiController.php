<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vivero;
use Illuminate\Http\Request;

class ModuloApiController extends Controller
{
    /**
     * Devuelve una lista de módulos que pertenecen a un vivero específico.
     *
     * @param  \App\Models\Vivero  $vivero
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModulosByVivero(Vivero $vivero)
    {
        // Retorna los módulos relacionados con el vivero en formato JSON.
        // El modelo Vivero debe tener una relación hasMany('modulos') definida.
        return response()->json($vivero->modulos);
    }
}
