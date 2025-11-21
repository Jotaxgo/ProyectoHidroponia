<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturaSensor extends Model
{
    use HasFactory;

    // Definimos el nombre de la tabla manualmente porque el nombre del modelo es singular
    protected $table = 'lecturas_sensores';

    protected $fillable = [
        'modulo_id',
        'temperatura',
        'ph',
        'humedad',
        'ec',
        'luz',
    ];

    
    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    /**
     * Calcula el estado de alerta para esta lectura.
     * Este es un "Accessor" que crea un atributo virtual.
     */
    protected function estadoAlerta(): Attribute
    {
        return Attribute::make(
            get: function () {
                $limits = config('hydroponics.limits');

                foreach ($limits as $key => $range) {
                    $value = $this->$key;
                    if ($value === null) {
                        continue; 
                    }

                    $min = $range['min'] ?? null;
                    $max = $range['max'] ?? null;

                    if (($min !== null && $value < $min) || ($max !== null && $value > $max)) {
                        return 'CRÍTICO';
                    }
                }

                return 'OK';
            }
        );
    }
}