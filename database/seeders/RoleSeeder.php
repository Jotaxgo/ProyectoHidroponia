<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // Asegúrate de importar el modelo Role

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['nombre_rol' => 'Admin']);
        Role::create(['nombre_rol' => 'Dueño de Vivero']);
    }
}
