<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // Asegúrate de importar el modelo Role

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::updateOrCreate(['nombre_rol' => 'Admin']);
        Role::updateOrCreate(['nombre_rol' => 'Dueño de Vivero']);
    }
}
