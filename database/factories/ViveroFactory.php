<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vivero>
 */
class ViveroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ciudades = [
            'La Paz' => ['lat' => -16.50, 'lon' => -68.15],
            'Santa Cruz de la Sierra' => ['lat' => -17.78, 'lon' => -63.18],
            'Cochabamba' => ['lat' => -17.39, 'lon' => -66.16],
            'Sucre' => ['lat' => -19.04, 'lon' => -65.26],
            'Oruro' => ['lat' => -17.98, 'lon' => -67.11],
            'Tarija' => ['lat' => -21.53, 'lon' => -64.73],
            'Potosí' => ['lat' => -19.58, 'lon' => -65.75],
            'El Alto' => ['lat' => -16.51, 'lon' => -68.17],
        ];

        $ciudadNombre = $this->faker->randomElement(array_keys($ciudades));
        $ciudadCoords = $ciudades[$ciudadNombre];

        return [
            'nombre' => 'Vivero ' . $ciudadNombre . ' ' . $this->faker->numberBetween(1, 5),
            'latitud' => $this->faker->latitude($ciudadCoords['lat'] - 0.05, $ciudadCoords['lat'] + 0.05),
            'longitud' => $this->faker->longitude($ciudadCoords['lon'] - 0.05, $ciudadCoords['lon'] + 0.05),
            'user_id' => User::factory(), // Asocia por defecto un nuevo usuario
        ];
    }
}
