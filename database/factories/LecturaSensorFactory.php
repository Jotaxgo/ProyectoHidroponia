<?php

namespace Database\Factories;

use App\Models\Modulo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LecturaSensor>
 */
class LecturaSensorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'modulo_id' => Modulo::factory(), // Asocia por defecto un nuevo módulo
            'temperatura' => $this->faker->randomFloat(2, 18, 28), // Grados Celsius
            'humedad' => $this->faker->randomFloat(2, 50, 75),     // Porcentaje
            'ph' => $this->faker->randomFloat(2, 5.5, 6.5),        // Nivel de pH
            'ec' => $this->faker->randomFloat(2, 1.2, 2.2),        // Conductividad Eléctrica
            'luz' => $this->faker->numberBetween(10000, 60000),    // Lúmenes
        ];
    }
}