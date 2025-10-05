<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- IMPORTANTE: Añadir esto

class Vivero extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'ubicacion',
        'descripcion',
    ];

    /**
     * Define la relación donde un Vivero tiene muchos Módulos.
     */
    public function modulos(): HasMany // <-- AÑADE TODA ESTA FUNCIÓN
    {
        return $this->hasMany(Modulo::class);
    }
}