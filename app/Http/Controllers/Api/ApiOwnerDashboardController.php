<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // <-- THE FIX
use App\Models\LecturaSensor;
use App\Models\Modulo;
use App\Models\SensorLimit;
use App\Models\Vivero;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiOwnerDashboardController extends Controller
{
    private const OFFLINE_THRESHOLD_MINUTES = 10;

    /**
     * Devuelve los datos de los módulos que pertenecen al usuario (dueño) autenticado.
     */
    public function getOwnerModuleData(Request $request, Vivero $vivero)
    {
        try {
            $user = $request->user();
            if ($user->id !== $vivero->user_id && $user->role->nombre_rol !== 'Admin') {
                return response()->json(['message' => 'No autorizado para este vivero.'], 403);
            }

            // 1. Cargar límites de sensores dinámicamente
            $defaultLimits = config('hydroponics.limits', []);
            $customLimitsRaw = SensorLimit::where('vivero_id', $vivero->id)->get();
            
            $customLimits = [];
            foreach ($customLimitsRaw as $limit) {
                $customLimits[$limit->sensor] = [
                    'min' => $limit->min,
                    'max' => $limit->max,
                ];
            }
            
            $limits = array_replace_recursive($defaultLimits, $customLimits);

            $modulosDelVivero = $vivero->modulos()->where('estado', '!=', 'Disponible')->get();
            $latestData = [];

            foreach ($modulosDelVivero as $modulo) {
                $latestReading = LecturaSensor::where('modulo_id', $modulo->id)->latest('created_at')->first();
                $estadoAlerta = 'OK';
                $tiempoDesdeUltimaLectura = null;
                $ultimaLecturaTimestamp = null;

                if ($latestReading) {
                    $ultimaLecturaTimestamp = Carbon::parse($latestReading->created_at);
                    $tiempoDesdeUltimaLectura = $ultimaLecturaTimestamp->diffInMinutes();

                    if ($tiempoDesdeUltimaLectura > self::OFFLINE_THRESHOLD_MINUTES) {
                        $estadoAlerta = 'OFFLINE';
                    } else {
                        $isCritical = false;
                        $isWarning = false;

                        foreach ($limits as $sensor => $range) {
                            if (!isset($latestReading->$sensor)) {
                                continue;
                            }

                            $value = (float)$latestReading->$sensor;
                            $min = $range['min'] ?? null;
                            $max = $range['max'] ?? null;

                            if ($sensor === 'ph') {
                                $criticalMin = ($min !== null) ? $min - 0.5 : null;
                                $criticalMax = ($max !== null) ? $max + 0.5 : null;
                                if (($criticalMin !== null && $value < $criticalMin) || ($criticalMax !== null && $value > $criticalMax)) {
                                    $isCritical = true;
                                    break;
                                }
                            }

                            if (($min !== null && $value < $min) || ($max !== null && $value > $max)) {
                                $isWarning = true;
                            }
                        }

                        if ($isCritical) {
                            $estadoAlerta = 'CRÍTICO';
                        } elseif ($isWarning) {
                            $estadoAlerta = 'ADVERTENCIA';
                        }
                    }
                } else {
                    $estadoAlerta = 'Sin Lecturas';
                }

                $latestData[] = [
                    'modulo_id' => $modulo->id,
                    'codigo' => $modulo->codigo_identificador,
                    'cultivo' => $modulo->cultivo_actual,
                    'ph' => $latestReading ? (float)$latestReading->ph : null,
                    'ec' => $latestReading ? (float)$latestReading->ec : null,
                    'temperatura' => $latestReading ? (float)$latestReading->temperatura : null,
                    'luz' => $latestReading ? (int)$latestReading->luz : null,
                    'humedad' => $latestReading ? (float)$latestReading->humedad : null,
                    'estado_alerta' => $estadoAlerta,
                    'minutos_offline' => $tiempoDesdeUltimaLectura,
                    'ultima_lectura' => $latestReading ? $ultimaLecturaTimestamp->format('H:i:s') : null,
                ];
            }
            return response()->json($latestData);
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error('API Error in getOwnerModuleData: ' . $th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());
            return response()->json(['message' => 'Error del servidor: ' . $th->getMessage()], 500);
        }
    }

    public function getModuleHistory(Request $request, Modulo $modulo)
    {
        $user = $request->user();
        if ($user->id !== $modulo->vivero->user_id && $user->role->nombre_rol !== 'Admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        $readings = LecturaSensor::where('modulo_id', $modulo->id)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->orderBy('created_at', 'asc')
            ->get();
        return response()->json($readings->map(function ($reading) {
            return [
                'ph' => (float)$reading->ph,
                'ec' => (float)$reading->ec,
                'temperatura' => (float)$reading->temperatura,
                'luz' => (int)$reading->luz,
                'humedad' => (float)$reading->humedad,
                'created_at' => Carbon::parse($reading->created_at)->toIso8601String(),
            ];
        }));
    }
}
