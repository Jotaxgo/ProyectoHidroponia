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
        // Obtener los roles y el vivero que ya deberían existir
        $adminRole = Role::where('nombre_rol', 'Admin')->first();
        $ownerRole = Role::where('nombre_rol', 'Dueño de Vivero')->first();
        $testVivero = Vivero::where('nombre', 'Vivero de Prueba')->first();

        // Crear Usuario Administrador (sin vivero)
        if ($adminRole) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@hidroponia.com',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole->id,
            ]);
        }

        // Crear Usuario Dueño de Vivero y asignarle el vivero de prueba
        if ($ownerRole && $testVivero) {
            $ownerUser = User::create([
                'name' => 'Juan Dueño',
                'email' => 'dueño@hidroponia.com',
                'password' => Hash::make('password'),
                'role_id' => $ownerRole->id,
            ]);

            // Usamos la tabla pivote para asignar el vivero a este usuario
            $ownerUser->viveros()->attach($testVivero->id);
        }
    }
}
