<?php

namespace App\Models;

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
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
}