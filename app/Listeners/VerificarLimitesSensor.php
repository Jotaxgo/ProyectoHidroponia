<?php

namespace App\Listeners;

use App\Events\LecturaSensorRegistrada;
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
        $limits = config('hydroponics.limits');
        $messages = [];

        // Mapeo de campos de lectura a nombres legibles
        $sensors = [
            'temperatura' => 'Temperatura del agua',
            'ph' => 'pH',
            'ec' => 'Conductividad Eléctrica (EC)',
            'humedad' => 'Humedad',
        ];

        foreach ($sensors as $key => $name) {
            if (!isset($lectura->$key)) {
                continue;
            }

            $value = $lectura->$key;
            $min = $limits[$key]['min'] ?? null;
            $max = $limits[$key]['max'] ?? null;

            if (($min !== null && $value < $min) || ($max !== null && $value > $max)) {
                $messages[] = "¡Alerta! {$name} está fuera de rango. Valor: {$value} (Rango ideal: {$min} - {$max})";
            }
        }

        if (!empty($messages)) {
            // Construimos la relación para obtener el usuario
            // with() se usa para cargar la relación y evitar N+1 queries si se hace en un bucle.
            $lectura->load('modulo.vivero.user');
            $user = $lectura->modulo->vivero->user;

            if ($user) {
                Notification::send($user, new AlertaSensorFueraDeRango($messages));
            }

            // También registramos en el log del sistema
            $logMessage = "Alertas para Módulo ID {$lectura->modulo_id}: " . implode(' | ', $messages);
            Log::warning($logMessage);
        }
    }
}
