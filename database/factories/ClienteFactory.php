<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ruta;
use App\Models\User;

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
        $ruta = Ruta::inRandomOrder()->first();
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
            'ruta_id' => $ruta?->id, // Asignar ruta más tarde si es necesario
            'comercial_id' => $this->faker->randomElement(User::where('role', 'COMERCIAL')->pluck('id')),
            'tipo_cliente' => $this->faker->randomElement(['ELECTRONICO', 'REMISIONADO']),
            'rut_imagen' => null, // o $this->faker->imageUrl(640, 480, 'clients', true)
            'retenedor_fuente' => $this->faker->randomElement(['SI', 'NO']),


        ];
    }
}
