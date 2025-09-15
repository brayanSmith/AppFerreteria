<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'tipo_documento' => $this->faker->randomElement(['DNI', 'RUC', 'CE']),
            'numero_documento' => $this->faker->unique()->numerify('##########'),
            'razon_social' => $this->faker->company(),
            'direccion' => $this->faker->address(),
            'telefono' => $this->faker->phoneNumber(),
            'ciudad' => $this->faker->city(),
            'email' => $this->faker->unique()->safeEmail(),
            'representante_legal' => $this->faker->name(),
            'activo' => $this->faker->boolean(90), // 90% de probabilidad de estar activo
            'novedad' => $this->faker->optional()->sentence(),

        ];
    }
}
