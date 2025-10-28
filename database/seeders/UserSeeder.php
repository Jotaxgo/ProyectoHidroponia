<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Vivero;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Usuario Administrador
        $adminRole = Role::where('nombre_rol', 'Admin')->first();
        if ($adminRole) {
            User::create([
                'nombres' => 'Admin',
                'primer_apellido' => 'del Sistema',
                'email' => 'admin@hidroponia.com',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole->id,
            ]);
        }

        // 2. Crear Usuario Dueño de Vivero de prueba
        $ownerRole = Role::where('nombre_rol', 'Dueño de Vivero')->first();
        if ($ownerRole) {
            $ownerUser = User::create([
                'nombres' => 'Juan',
                'primer_apellido' => 'Dueño',
                'email' => 'dueno@hidroponia.com',
                'password' => Hash::make('password'),
                'role_id' => $ownerRole->id,
            ]);

            // 3. Crear un Vivero y asignárselo a este nuevo dueño
            Vivero::create([
                'user_id' => $ownerUser->id,
                'nombre' => 'Vivero de Juan',
                'latitud' => -17.3895,  // Ejemplo de Latitud
                'longitud' => -66.1568, // Ejemplo de Longitud
                'descripcion' => 'Este es el primer vivero de prueba.'
            ]);
        }
    }
}