<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proveedor>
 */
class ProveedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bancos = [
            'Bancolombia', 'Davivienda', 'BBVA', 'Banco de Bogotá',
            'Banco Popular', 'Scotiabank Colpatria', 'Av Villas', 'Nequi'
        ];

        return [
            // vamos a crear unos 5 proveedores de ejemplo
            'nombre_proveedor' => $this->faker->company(),
            'razon_social_proveedor' => $this->faker->companySuffix(),
            'nit_proveedor' => $this->faker->unique()->numerify('NIT-########'),
            'rut_proveedor_imagen' => null,
            'tipo_proveedor' => $this->faker->randomElement(['REMISIONADO', 'ELECTRONICO']),
            'categoria_proveedor' => $this->faker->randomElement(['DECLARANTE', 'NO_DECLARANTE', 'RETENEDOR']),
            'departamento_proveedor' => $this->faker->state(),
            'ciudad_proveedor' => $this->faker->city(),
            'direccion_proveedor' => $this->faker->address(),
            'telefono_proveedor' => $this->faker->phoneNumber(),
            // reemplazo para evitar formatos no soportados por Faker instalado
            'banco_proveedor' => $this->faker->randomElement($bancos),
            'tipo_cuenta_proveedor' => $this->faker->randomElement(['AHORRO', 'CORRIENTE']),
            // generar número de cuenta con numerify (ej: 20 dígitos)
            'numero_cuenta_proveedor' => $this->faker->numerify('####################'),
        ];
    }
}
