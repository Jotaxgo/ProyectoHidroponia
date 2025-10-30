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
     */
    public function getLatestModuleData()
    {
        // 1. Seleccionar 'cultivo_actual'
        $modulosActivos = Modulo::where('estado', '!=', 'Disponible')
            ->select('id', 'codigo_identificador', 'cultivo_actual') // Asegurado
            ->get();

        $latestData = [];

        foreach ($modulosActivos as $modulo) {
            $latestReading = LecturaSensor::where('modulo_id', $modulo->id)
                ->latest('created_at')
                ->first();

            $estadoAlerta = 'OK';
            $tiempoDesdeUltimaLectura = null;
            $ultimaLecturaTimestamp = null;

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
                'nombre_modulo' => $modulo->codigo_identificador,
                'cultivo_actual' => $modulo->cultivo_actual,
                
                'ph' => $latestReading ? (float)$latestReading->ph : null, 
                'ec' => $latestReading ? (float)$latestReading->ec : null,
                'temperatura' => $latestReading ? (float)$latestReading->temperatura : null,
                
                // --- CAMPOS NUEVOS ---
                'luz' => $latestReading ? (int)$latestReading->luz : null,
                'humedad' => $latestReading ? (float)$latestReading->humedad : null, // Asumiendo que tienes 'humedad'
                // --- FIN CAMPOS NUEVOS ---
                
                'estado_alerta' => $estadoAlerta,
                'minutos_offline' => $tiempoDesdeUltimaLectura,
                'hora_ultima_lectura' => $latestReading ? $ultimaLecturaTimestamp->format('H:i:s') : null 
            ];
            // --- FIN MODIFICACIÓN ---
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
