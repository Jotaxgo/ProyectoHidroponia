<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'hardware_info' => 'array', // <-- LA LÍNEA QUE FALTABA
    ];

    /**
     * Obtiene el vivero al que pertenece el módulo.
     */
    public function vivero(): BelongsTo
    {
        return $this->belongsTo(Vivero::class);
    }
}
