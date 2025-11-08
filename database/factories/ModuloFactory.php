<?php

namespace Database\Factories;

use App\Models\Vivero;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Modulo>
 */
class ModuloFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'codigo_identificador' => 'MOD-' . $this->faker->unique()->bothify('??##??'),
            'capacidad' => $this->faker->numberBetween(10, 100),
            'vivero_id' => Vivero::factory(), // Asocia por defecto un nuevo vivero
        ];
    }
}