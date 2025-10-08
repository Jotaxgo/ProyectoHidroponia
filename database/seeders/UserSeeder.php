<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Vivero; // <-- Importante
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Usuario Administrador (sin vivero)
        $adminRole = Role::where('nombre_rol', 'Admin')->first();
        if ($adminRole) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@hidroponia.com',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole->id,
            ]);
        }

        // 2. Crear Usuario Dueño de Vivero
        $ownerRole = Role::where('nombre_rol', 'Dueño de Vivero')->first();
        if ($ownerRole) {
            $ownerUser = User::create([
                'name' => 'Juan Dueño',
                'email' => 'dueno@hidroponia.com',
                'password' => Hash::make('password'),
                'role_id' => $ownerRole->id,
            ]);

            // 3. Crear un Vivero y asignárselo a este nuevo dueño
            Vivero::create([
                'user_id' => $ownerUser->id, // <-- La clave está aquí
                'nombre' => 'Vivero de Juan',
                'ubicacion' => 'Calle Falsa 123',
                'descripcion' => 'Este es el primer vivero de prueba.'
            ]);
        }
    }
}