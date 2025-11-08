<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nombres = ['Juan', 'Marcelo', 'Luis', 'Carlos', 'Ricardo', 'Jose', 'Miguel', 'Pedro', 'Raul', 'Marco'];
        $nombres_mujer = ['Maria', 'Ana', 'Sofia', 'Laura', 'Isabel', 'Carmen', 'Patricia', 'Lucia', 'Elena', 'Claudia'];
        $apellidos = ['Mamani', 'Quispe', 'Flores', 'Condori', 'Choque', 'Vargas', 'Rojas', 'Gutiérrez', 'Perez', 'Lopez'];

        $nombre = $this->faker->randomElement(array_merge($nombres, $nombres_mujer));

        return [
            'nombres' => $nombre,
            'primer_apellido' => $this->faker->randomElement($apellidos),
            'segundo_apellido' => $this->faker->randomElement($apellidos),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}