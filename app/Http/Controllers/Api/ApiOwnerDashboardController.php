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
     * Devuelve los datos de los módulos que pertenecen a un VIVERO específico.
     */
    // El método ahora recibe el Vivero desde la ruta (Model Binding)

    public function getOwnerModuleData(Request $request, Vivero $vivero)
    {
        $user = $request->user();

        // --- Verificación de Seguridad (AHORA DEBERÍA FUNCIONAR) ---
        // Permitimos el acceso si es el dueño O si es Admin
        if ($user->id !== $vivero->user_id && $user->role->nombre_rol !== 'Admin') {
            // Si esto falla, devolverá el 403 original, pero ahora sabemos que los IDs coinciden.
            return response()->json(['message' => 'No autorizado para este vivero.'], 403);
        }

        // --- CÓDIGO ORIGINAL RESTAURADO ---

        // 2. Obtenemos los módulos que pertenecen a ESE vivero
        try {
            $modulosDelVivero = $vivero->modulos;
            if ($modulosDelVivero === null) {
                // Esto puede pasar si la relación modulos() no está definida en el modelo Vivero
                return response()->json(['message' => 'Relación de módulos no encontrada para este vivero.', 'data' => []], 500);
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

            if ($latestReading) {
                // 5. Calculamos el estado (OK, Crítico, Offline)
                $tiempoDesdeUltimaLectura = Carbon::parse($latestReading->created_at)->diffInMinutes();

                if ($tiempoDesdeUltimaLectura > self::OFFLINE_THRESHOLD_MINUTES) {
                    $estadoAlerta = 'OFFLINE';
                } elseif ($latestReading->ph < (self::PH_MIN - 0.5) || $latestReading->ph > (self::PH_MAX + 0.5)) {
                    $estadoAlerta = 'CRÍTICO';
                } elseif ($latestReading->ph < self::PH_MIN || $latestReading->ph > self::PH_MAX || $latestReading->temperatura > self::TEMP_MAX) {
                    $estadoAlerta = 'ADVERTENCIA';
                }

                // 6. Preparamos los datos
                $latestData[] = [
                    'modulo_id' => $modulo->id,
                    'codigo' => $modulo->codigo_identificador,
                    'cultivo' => $modulo->cultivo_actual,
                    'ph' => round($latestReading->ph, 2),
                    'ec' => round($latestReading->ec, 2),
                    'temperatura' => round($latestReading->temperatura, 1),
                    'ultima_lectura' => $latestReading->created_at->format('H:i:s'),
                    'minutos_offline' => $tiempoDesdeUltimaLectura,
                    'estado_alerta' => $estadoAlerta,
                ];
            } else {
                // Si el módulo está "Ocupado" pero NUNCA ha enviado datos
                $latestData[] = [
                    'modulo_id' => $modulo->id,
                    'codigo' => $modulo->codigo_identificador,
                    'cultivo' => $modulo->cultivo_actual,
                    'estado_alerta' => 'OFFLINE',
                    'minutos_offline' => null,
                ];
            }
        }
        // 7. Devolvemos el JSON con los datos de los sensores
        return response()->json($latestData);
    }
}