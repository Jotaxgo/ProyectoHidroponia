<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vivero;

class ViveroSeeder extends Seeder
{
    public function run(): void
    {
        Vivero::create([
            'nombre' => 'Vivero de Prueba',
            'ubicacion' => 'Calle Falsa 123',
            'descripcion' => 'Este es un vivero creado automáticamente para pruebas.'
        ]);
    }
}
