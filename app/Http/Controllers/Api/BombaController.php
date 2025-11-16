<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use Illuminate\Http\Request;

class BombaController extends Controller
{
    // ESP32 CONSULTA EL ESTADO DE LA BOMBA
    public function estado($device_id)
    {
        $modulo = Modulo::where('hardware_info->device_id', $device_id)->first();

        if (!$modulo) {
            return response()->json(['error' => 'Dispositivo no encontrado'], 404);
        }

        return response()->json([
            'bomba_estado' => (bool) $modulo->bomba_estado
        ]);
    }

    // LA WEB CAMBIA MANUALMENTE EL ESTADO
    public function cambiarEstado(Request $request, $device_id)
    {
        $modulo = Modulo::where('hardware_info->device_id', $device_id)->first();

        if (!$modulo) {
            return response()->json(['error' => 'Dispositivo no encontrado'], 404);
        }

        $request->validate([
            'estado' => 'required|boolean'
        ]);

        $modulo->bomba_estado = $request->estado;
        $modulo->save();

        return response()->json([
            'mensaje' => 'Estado actualizado correctamente',
            'bomba_estado' => $modulo->bomba_estado
        ]);
    }
}
