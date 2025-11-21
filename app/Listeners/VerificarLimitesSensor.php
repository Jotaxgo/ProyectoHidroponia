<?php

namespace App\Listeners;

use App\Events\LecturaSensorRegistrada;
use App\Models\SensorLimit;
use App\Notifications\AlertaSensorFueraDeRango;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class VerificarLimitesSensor
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LecturaSensorRegistrada $event): void
    {
        $lectura = $event->lectura;
        
        // Cargar el vivero para obtener sus limites
        $lectura->load('modulo.vivero');
        $vivero = $lectura->modulo->vivero;

        // 1. Obtener los límites por defecto desde el archivo de configuración.
        $defaultLimits = config('hydroponics.limits', []);

        // 2. Obtener los límites personalizados de la base de datos para este vivero.
        $customLimitsRaw = SensorLimit::where('vivero_id', $vivero->id)->get();
        
        // 3. Formatear los límites personalizados para que coincidan con la estructura de los por defecto.
        $customLimits = [];
        foreach ($customLimitsRaw as $limit) {
            $customLimits[$limit->sensor] = [
                'min' => $limit->min,
                'max' => $limit->max,
            ];
        }

        // 4. Fusionar los límites. Los personalizados sobreescriben a los por defecto.
        $limits = array_merge($defaultLimits, $customLimits);

        $messages = [];

        // Mapeo de campos de lectura a nombres legibles
        $sensors = [
            'temperatura' => 'Temperatura del agua',
            'ph' => 'pH',
            'ec' => 'Conductividad Eléctrica (EC)',
            'humedad' => 'Humedad',
            'luz' => 'Luminosidad',
        ];

        foreach ($sensors as $key => $name) {
            if (!isset($lectura->$key)) {
                continue;
            }

            $value = $lectura->$key;
            // Asegurarnos que hay límites definidos para este sensor
            if (!isset($limits[$key])) {
                continue;
            }
            $min = $limits[$key]['min'] ?? null;
            $max = $limits[$key]['max'] ?? null;

            if (($min !== null && $value < $min) || ($max !== null && $value > $max)) {
                $messages[] = "¡Alerta! {$name} está fuera de rango. Valor: {$value} (Rango ideal: {$min} - {$max})";
            }
        }

        if (!empty($messages)) {
            // Cargar la relación del usuario solo si es necesario
            $vivero->load('user');
            $user = $vivero->user;

            if ($user) {
                Notification::send($user, new AlertaSensorFueraDeRango($messages));
            }

            // También registramos en el log del sistema
            $logMessage = "Alertas para Módulo ID {$lectura->modulo_id}: " . implode(' | ', $messages);
            Log::warning($logMessage);
        }
    }
}
