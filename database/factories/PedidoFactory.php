<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Pedido;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pedido>
 */
class PedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Pedido::class;
    public function definition(): array
    {
        return [
            'codigo' => $this->faker->unique()->bothify('PED-####'),
            'cliente_id' => Cliente::factory(), // crea cliente si no existe
            'fecha' => $this->faker->dateTimeThisYear(),
            'fecha_sola' => $this->faker->date(),
            'ciudad' => $this->faker->city(),
            'estado' => $this->faker->randomElement(['PENDIENTE', 'FACTURADO', 'ANULADO']),
            'en_cartera' => $this->faker->boolean(),
            'metodo_pago' => $this->faker->randomElement(['A CREDITO', 'EFECTIVO']),
            'tipo_precio' => $this->faker->randomElement(['FERRETERO', 'MAYORISTA', 'DETAL']),
            'tipo_venta' => $this->faker->randomElement(['ELECTRICA', 'REMISIONADA']),
            'primer_comentario' => $this->faker->sentence(),
            'segundo_comentario' => $this->faker->optional()->sentence(),
            'subtotal' => 0, // lo calcularemos con los detalles
        ];
    }
}
