<?php

namespace Database\Seeders;

use App\Models\LecturaSensor;
use App\Models\Modulo;
use App\Models\Role;
use App\Models\User;
use App\Models\Vivero;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatosDePruebaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Iniciando el seeder de datos de prueba...');

        // --- Obtener Roles ---
        $rolAdmin = Role::where('nombre_rol', 'Admin')->first();
        $rolDueno = Role::where('nombre_rol', 'Dueño de Vivero')->first();

        if (!$rolAdmin || !$rolDueno) {
            $this->command->error('Los roles "Admin" o "Dueño de Vivero" no se encontraron. Ejecuta RoleSeeder primero.');
            return;
        }

        // --- Crear Usuarios Admin ---
        $this->command->info('Creando usuarios Administradores...');
        User::factory(5)->create([
            'role_id' => $rolAdmin->id,
            'password' => Hash::make('password'),
            'estado' => 'activo',
        ]);

        // --- Crear Dueños de Vivero y sus estructuras ---
        $this->command->info('Creando Dueños de Vivero, Viveros y Módulos...');
        $duenos = User::factory(10)->create([
            'role_id' => $rolDueno->id,
            'password' => Hash::make('password'),
            'estado' => 'activo',
        ]);

        foreach ($duenos as $dueno) {
            // Para cada dueño, crear entre 1 y 3 viveros
            $viveros = Vivero::factory(rand(1, 3))->create([
                'user_id' => $dueno->id
            ]);

            foreach ($viveros as $vivero) {
                // Para cada vivero, crear entre 2 y 5 módulos
                $modulos = Modulo::factory(rand(2, 5))->create([
                    'vivero_id' => $vivero->id
                ]);

                // --- Generar Lecturas de Sensores para cada Módulo ---
                $this->command->info("Generando lecturas para los módulos del vivero {$vivero->nombre}...");
                foreach ($modulos as $modulo) {
                    $this->generarLecturasParaModulo($modulo);
                }
            }
        }

        $this->command->info('¡Seeder de datos de prueba completado!');
    }

    /**
     * Genera lecturas de sensores para un módulo específico durante los últimos 90 días.
     *
     * @param Modulo $modulo
     */
    private function generarLecturasParaModulo(Modulo $modulo): void
    {
        $lecturas = [];
        $fechaActual = now();

        // Iterar desde hace 90 días hasta hoy, cada 4 horas
        for ($d = 90; $d >= 0; $d--) {
            for ($h = 0; $h < 24; $h += 4) {
                $fechaLectura = now()->subDays($d)->startOfDay()->addHours($h);

                // No generar lecturas en el futuro
                if ($fechaLectura->isFuture()) {
                    continue;
                }

                $lecturas[] = array_merge(
                    LecturaSensor::factory()->definition(),
                    [
                        'modulo_id' => $modulo->id,
                        'created_at' => $fechaLectura->format('Y-m-d H:i:s'),
                        'updated_at' => $fechaLectura->format('Y-m-d H:i:s'),
                    ]
                );
            }
        }

        // Insertar todas las lecturas en la base de datos de una sola vez para mayor eficiencia
        if (!empty($lecturas)) {
            LecturaSensor::insert($lecturas);
        }
    }
}