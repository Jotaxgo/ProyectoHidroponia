<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- IMPORTANTE

class Vivero extends Model
{
    use HasFactory, SoftDeletes; // <-- IMPORTANTE

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'ubicacion',
        'descripcion',
    ];

    // Aquí definiremos relaciones futuras, como la de los Módulos.
}