<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modulo extends Model
{
    use HasFactory;
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'codigo_identificador',
        'capacidad',
        'estado',
        'vivero_id',
    ];

    /**
     * Obtiene el vivero al que pertenece el módulo.
     */
    public function vivero(): BelongsTo
    {
        return $this->belongsTo(Vivero::class);
    }
}
