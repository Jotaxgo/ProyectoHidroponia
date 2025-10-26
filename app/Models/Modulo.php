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
}