<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LecturaSensor;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturaSensorController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validar los datos que llegan del ESP32
        $validated = $request->validate([
            'device_id'   => 'required|string|exists:modulos,hardware_info->device_id',
            'temperatura' => 'nullable|numeric',
            'ph'          => 'nullable|numeric',
            'ec'          => 'nullable|numeric',
            'luz'         => 'nullable|numeric', // <-- CORRECCIÓN AQUÍ
            'humedad'         => 'nullable|numeric',
        ]);

        // 2. Encontrar el módulo que corresponde al device_id
        $modulo = Modulo::where('hardware_info->device_id', $validated['device_id'])->firstOrFail();

        // 3. Verificar que el dueño del módulo sea el mismo que el dueño del token
        if (Auth::user()->id !== $modulo->vivero->user_id) {
            return response()->json(['message' => 'No autorizado para este módulo'], 403);
        }

        // 4. Crear la nueva lectura en la base de datos
        $lectura = $modulo->lecturas()->create([
            'temperatura' => $validated['temperatura'] ?? null,
            'ph'          => $validated['ph'] ?? null,
            'ec'          => $validated['ec'] ?? null,
            'luz'         => $validated['luz'] ?? null,
            'humedad'         => $validated['humedad'] ?? null,
        ]);

        // 5. Devolver una respuesta exitosa
        return response()->json([
            'message' => 'Lectura guardada exitosamente.',
            'data' => $lectura
        ], 201);
    }
}
