<?php

return [
    'limits' => [
        'ph' => [
            'min' => 5.5,
            'max' => 6.5,
        ],
        // Asumo que 'temperatura' en tu tabla es la del agua.
        'temperatura' => [
            'min' => 18.0,
            'max' => 22.0,
        ],
        'ec' => [
            'min' => 1.0, // Un poco más bajo para incluir plantas jóvenes
            'max' => 2.5, // Un poco más alto para margen de error
        ],
        'humedad' => [
            'min' => 55.0,
            'max' => 80.0, // Alerta si se acerca a niveles peligrosos
        ],
        // 'luz' es más un acumulado diario (DLI) o un ciclo (on/off),
        // por lo que un min/max simple no es tan útil para alertas.
        // Lo omito por ahora a menos que tengas una idea específica.
    ],
];
