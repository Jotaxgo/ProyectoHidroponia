<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LecturaSensor;
use App\Models\Modulo;
use App\Models\Vivero; // <-- Importar el modelo Vivero
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ApiOwnerDashboardController extends Controller
{
    // --- Constantes para los rangos de alerta ---
    private const PH_MIN = 5.8;
    private const PH_MAX = 6.4;
    private const TEMP_MAX = 25.0; // Temperatura máxima del agua
    private const OFFLINE_THRESHOLD_MINUTES = 10; // Minutos para considerar un módulo "OFFLINE"

    /**
     * Devuelve los datos de los módulos que pertenecen al usuario (dueño) autenticado.
     */
    public function getOwnerModuleData(Request $request, Vivero $vivero)
    {
        $user = $request->user();

        // --- Verificación de Seguridad (OK) ---
        if ($user->id !== $vivero->user_id && $user->role->nombre_rol !== 'Admin') {
            return response()->json(['message' => 'No autorizado para este vivero.'], 403);
        }

        // 2. Obtenemos los módulos que pertenecen a ESE vivero
        try {
            $modulosDelVivero = $vivero->modulos;
            if ($modulosDelVivero === null) {
                 return response()->json(['message' => 'Relación de módulos no encontrada.', 'data' => []], 500);
            }
        } catch (\Exception $e) {
             return response()->json(['message' => 'Error al obtener módulos: ' . $e->getMessage(), 'data' => []], 500);
        }
        
        $latestData = [];

        // 3. Iteramos sobre CADA módulo
        foreach ($modulosDelVivero as $modulo) {
            
            if ($modulo->estado === 'Disponible') {
                continue; 
            }

            // 4. Buscamos la ÚLTIMA lectura
            $latestReading = LecturaSensor::where('modulo_id', $modulo->id)
                ->latest('created_at')
                ->first();

            $estadoAlerta = 'OK';
            $tiempoDesdeUltimaLectura = null;
            $ultimaLecturaTimestamp = null; // Para cálculo preciso del tiempo

            if ($latestReading) {
                $ultimaLecturaTimestamp = Carbon::parse($latestReading->created_at);
                $tiempoDesdeUltimaLectura = $ultimaLecturaTimestamp->diffInMinutes();

                // Convertimos a float para comparaciones seguras
                $phVal = (float)$latestReading->ph;
                $tempVal = (float)$latestReading->temperatura;

                if ($tiempoDesdeUltimaLectura > self::OFFLINE_THRESHOLD_MINUTES) {
                    $estadoAlerta = 'OFFLINE';
                } elseif ($phVal < (self::PH_MIN - 0.5) || $phVal > (self::PH_MAX + 0.5)) {
                    $estadoAlerta = 'CRÍTICO';
                } elseif ($phVal < self::PH_MIN || $phVal > self::PH_MAX || $tempVal > self::TEMP_MAX) {
                    $estadoAlerta = 'ADVERTENCIA';
                }
            } else {
                $estadoAlerta = 'Sin Lecturas'; 
            }

            // --- AÑADIMOS LUZ Y HUMEDAD AL ARRAY ---
            $latestData[] = [
                'modulo_id' => $modulo->id,
                'codigo' => $modulo->codigo_identificador,
                'cultivo' => $modulo->cultivo_actual,
                
                'ph' => $latestReading ? (float)$latestReading->ph : null, 
                'ec' => $latestReading ? (float)$latestReading->ec : null,
                'temperatura' => $latestReading ? (float)$latestReading->temperatura : null,
                
                // --- CAMPOS NUEVOS ---
                'luz' => $latestReading ? (int)$latestReading->luz : null,
                'humedad' => $latestReading ? (float)$latestReading->humedad : null,
                // --- FIN CAMPOS NUEVOS ---
                
                'estado_alerta' => $estadoAlerta,
                'minutos_offline' => $tiempoDesdeUltimaLectura,
                'ultima_lectura' => $latestReading ? $ultimaLecturaTimestamp->format('H:i:s') : null // Nombre corregido
            ];
            // --- FIN MODIFICACIÓN ---
        }
        // 7. Devolvemos el JSON
        return response()->json($latestData);
    }
}