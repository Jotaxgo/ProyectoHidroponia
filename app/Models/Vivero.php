<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Asegúrate de tener esto
use Illuminate\Database\Eloquent\Relations\HasMany;   // Asegúrate de tener esto

class Vivero extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'user_id',
        'latitud',
        'longitud',
        'descripcion',
    ];

    /**
     * Define la relación donde un Vivero pertenece a un Usuario (dueño).
     * ESTA ES LA FUNCIÓN CLAVE QUE NECESITAMOS.
     */
    public function user(): BelongsTo
    {
        // Asegúrate de que el nombre del modelo 'User' sea correcto
        return $this->belongsTo(User::class)->withTrashed(); 
    }

    /**
     * Define la relación donde un Vivero tiene muchos Módulos.
     */
    public function modulos(): HasMany
    {
        // Asegúrate de que el nombre del modelo 'Modulo' sea correcto
        return $this->hasMany(Modulo::class); 
    }
}