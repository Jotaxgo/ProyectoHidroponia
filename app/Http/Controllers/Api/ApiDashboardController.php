<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LecturaSensor; 
use App\Models\Modulo;       
use Illuminate\Http\Request;
use Carbon\Carbon; // Necesario para calcular diferencias de tiempo

class ApiDashboardController extends Controller
{
    // =========================================================
    // RANGOS DE MONITOREO (CONSTANTES DE CLASE) - ¡CORREGIDO!
    // =========================================================
    private const PH_MIN = 5.8;
    private const PH_MAX = 6.4;
    private const TEMP_MAX = 25.0;
    private const OFFLINE_THRESHOLD_MINUTES = 10; // Si no hay lectura en 10 minutos, se considera offline

    /**
     * Devuelve la última lectura de cada módulo de cultivo activo, optimizado para tabla resumen.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLatestModuleData()
    {
        $modulosActivos = Modulo::where('estado', '!=', 'Disponible')
            ->select('id', 'codigo_identificador', 'cultivo_actual') // Añadimos 'cultivo_actual'
            ->get();

        $latestData = [];

        foreach ($modulosActivos as $modulo) {
            
            $latestReading = LecturaSensor::where('modulo_id', $modulo->id)
                ->latest('created_at') 
                ->first();
            
            $estadoAlerta = 'OK';
            $tiempoDesdeUltimaLectura = null;

            if ($latestReading) {
                // Calcular tiempo desde la última lectura
                $tiempoDesdeUltimaLectura = Carbon::parse($latestReading->created_at)->diffInMinutes();

                // 1. Verificar estado Offline (Usando self:: para referenciar las constantes)
                if ($tiempoDesdeUltimaLectura > self::OFFLINE_THRESHOLD_MINUTES) {
                    $estadoAlerta = 'OFFLINE';
                }
                
                // 2. Verificar estado Crítico/Advertencia (PH)
                elseif ($latestReading->ph < (self::PH_MIN - 0.5) || $latestReading->ph > (self::PH_MAX + 0.5)) {
                    $estadoAlerta = 'CRÍTICO';
                } 
                // 3. Verificar estado Advertencia (PH o Temp)
                elseif ($latestReading->ph < self::PH_MIN || $latestReading->ph > self::PH_MAX || $latestReading->temperatura > self::TEMP_MAX) {
                    $estadoAlerta = 'ADVERTENCIA';
                }

                // 4. Formatear los datos
                $latestData[] = [
                    'modulo_id' => $modulo->id,
                    'codigo' => $modulo->codigo_identificador, 
                    'cultivo' => $modulo->cultivo_actual,
                    'ph' => round($latestReading->ph, 2), 
                    'ec' => round($latestReading->ec, 2),
                    'temperatura' => round($latestReading->temperatura, 1),
                    'ultima_lectura' => $latestReading->created_at->format('H:i:s'),
                    'minutos_offline' => $tiempoDesdeUltimaLectura,
                    'estado_alerta' => $estadoAlerta, // OK, ADVERTENCIA, CRÍTICO, OFFLINE
                ];

            } else {
                // Módulo Activo sin Lecturas Iniciales (también se marca como OFFLINE)
                $latestData[] = [
                    'modulo_id' => $modulo->id,
                    'codigo' => $modulo->codigo_identificador, 
                    'cultivo' => $modulo->cultivo_actual,
                    'estado_alerta' => 'OFFLINE', 
                    'minutos_offline' => null,
                ];
            }
        }

        return response()->json($latestData);
    }
}
