<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Asegúrate de tener esto
use Illuminate\Database\Eloquent\SoftDeletes;

class Modulo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codigo_identificador',
        'capacidad',
        'estado',
        'vivero_id',
        'cultivo_actual',
        'fecha_siembra',
        'hardware_info',
    ];

    protected $casts = [
        'hardware_info' => 'array',
    ];

    // 1. RELACIÓN AL VIVERO AL QUE PERTENECE
    public function vivero(): BelongsTo
    {
        return $this->belongsTo(Vivero::class);
    }

    /**
     * Define la relación donde un Módulo tiene muchas Lecturas de Sensores.
     */
    public function lecturas(): HasMany
    {
        return $this->hasMany(LecturaSensor::class);
    }

    /**
     * Define la relación para obtener solo la última lectura de sensor.
     */
    public function latestLectura()
    {
        return $this->hasOne(LecturaSensor::class)->latestOfMany();
    }

    // 2. RELACIÓN AL DUEÑO DEL VIVERO
    // Asumimos que el modelo Vivero tiene un campo 'user_id' o 'dueno_id'
    public function dueno()
    {
        // Esto asume que el modelo Vivero tiene una relación 'user' o 'dueno'
        // Es una relación de HAS ONE THROUGH si no existe una directa
        return $this->hasOneThrough(
            User::class,     // El modelo final que queremos
            Vivero::class,   // Modelo intermedio
            'user_id',       // Foreign key en la tabla viveros
            'id',            // Local key en la tabla users
            'vivero_id',     // Local key en la tabla modulos
            'id'             // Foreign key en la tabla users (si tu tabla viveros usa 'user_id' como FK)
        );
        
        /* Si la relación te da problemas, usa la simple a través del vivero:
        public function dueno() {
            return $this->vivero->user(); // Asumiendo que Vivero.php tiene una relación 'user()'
        }
        */
    }
}