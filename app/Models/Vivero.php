<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vivero extends Model
{
    use HasFactory;

    public function viveros()
    {
        return $this->belongsToMany(Vivero::class, 'user_vivero');
    }
}
