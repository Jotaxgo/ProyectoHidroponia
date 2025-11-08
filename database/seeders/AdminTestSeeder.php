<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔐 Creando usuario Admin de prueba...');

        // Obtener el rol Admin
        $rolAdmin = Role::where('nombre_rol', 'Admin')->first();

        if (!$rolAdmin) {
            $this->command->error('❌ El rol "Admin" no se encontró. Ejecuta RoleSeeder primero.');
            return;
        }

        // Crear o actualizar el usuario admin de prueba
        $admin = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'nombres' => 'Admin',
                'primer_apellido' => 'Test',
                'segundo_apellido' => 'Sistema',
                'role_id' => $rolAdmin->id,
                'password' => Hash::make('123456'),
                'estado' => 'activo',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Admin de prueba creado exitosamente!');
        $this->command->warn('📧 Email: admin@test.com');
        $this->command->warn('🔑 Contraseña: 123456');
    }
}
