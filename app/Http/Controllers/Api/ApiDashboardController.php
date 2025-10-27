<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LecturaSensor; 
use App\Models\Modulo;       
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiDashboardController extends Controller
{
    // Constantes para la validación y presentación del dashboard
    private const PH_MIN = 5.8;
    private const PH_MAX = 6.4;
    private const EC_MIN = 1.0; 
    private const EC_MAX = 2.5; 
    private const TEMP_MAX = 25.0;
    private const OFFLINE_THRESHOLD_MINUTES = 10; // Si no hay lectura en 10 minutos, se marca como offline

    /**
     * Devuelve la última lectura de cada módulo de cultivo activo.
     * ... (código getLatestModuleData anterior)
     */
    public function getLatestModuleData()
    {
        // ... (código getLatestModuleData anterior, asumo que está correcto)
        $modulosActivos = Modulo::where('estado', '!=', 'Disponible')
            ->select('id', 'codigo_identificador')
            ->get();

        $latestData = [];

        foreach ($modulosActivos as $modulo) {
            
            $latestReading = LecturaSensor::where('modulo_id', $modulo->id)
                ->latest('created_at') 
                ->first();

            if ($latestReading) {
                // Cálculo de tiempo para el estado offline
                $tiempoDesdeUltimaLectura = Carbon::parse($latestReading->created_at)->diffInMinutes();
                $isOffline = $tiempoDesdeUltimaLectura > self::OFFLINE_THRESHOLD_MINUTES;

                // Lógica de alerta para la tabla
                $estadoAlerta = 'OK';
                if ($isOffline) {
                    $estadoAlerta = 'Offline';
                } elseif ($latestReading->ph < self::PH_MIN || $latestReading->ph > self::PH_MAX) {
                    $estadoAlerta = 'Crítico (pH)';
                } elseif ($latestReading->ec < self::EC_MIN || $latestReading->ec > self::EC_MAX) {
                    $estadoAlerta = 'Crítico (EC)';
                }
                
                $latestData[] = [
                    'modulo_id' => $modulo->id,
                    'nombre_modulo' => $modulo->codigo_identificador, 
                    'ph' => $latestReading->ph, 
                    'ec' => $latestReading->ec,
                    'temperatura' => $latestReading->temperatura, 
                    'luz' => $latestReading->luz,
                    'ultimo_reporte_minutos' => $isOffline ? $tiempoDesdeUltimaLectura : null,
                    'estado_alerta' => $estadoAlerta,
                ];
            } else {
                $latestData[] = [
                    'modulo_id' => $modulo->id,
                    'nombre_modulo' => $modulo->codigo_identificador, 
                    'estado_alerta' => 'Sin Lecturas',
                ];
            }
        }

        return response()->json($latestData);
    }

    /**
     * Obtiene el historial de lecturas (últimas 24 horas) para un módulo específico.
     * Devuelve los datos de 4 sensores: PH, Temperatura, EC y Luz.
     * @param Modulo $modulo El módulo extraído automáticamente por Laravel.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModuleHistory(Modulo $modulo)
    {
        // Define el rango de tiempo: Desde hace 24 horas hasta ahora
        $last24Hours = Carbon::now()->subHours(24);

        // 1. Obtener los datos de la base de datos
        $readings = LecturaSensor::where('modulo_id', $modulo->id)
            ->where('created_at', '>=', $last24Hours)
            ->orderBy('created_at', 'asc')
            // Obtener todos los sensores requeridos
            ->select('ph', 'temperatura', 'ec', 'luz', 'created_at') 
            ->get();

        // 2. Formatear los datos para Chart.js
        $labels = [];       
        $phData = [];       
        $tempData = [];     
        $ecData = [];       // <-- NUEVO: Datos de EC
        $luzData = [];      // <-- NUEVO: Datos de Luz

        foreach ($readings as $reading) {
            $labels[] = $reading->created_at->isoFormat('HH:mm'); 
            $phData[] = $reading->ph;
            $tempData[] = $reading->temperatura;
            $ecData[] = $reading->ec;   // <-- Añadido
            $luzData[] = $reading->luz; // <-- Añadido
        }

        // 3. Devolver los cuatro conjuntos de datos formateados
        return response()->json([
            'labels' => $labels,
            'ph' => $phData,
            'temperatura' => $tempData,
            'ec' => $ecData, // <-- Añadido
            'luz' => $luzData, // <-- Añadido
        ]);
    }
}
