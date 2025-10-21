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
        $fechaVenc = $this->faker->optional()->dateTimeBetween('now', '+1 month');

        return [
            'bodega_id' => \App\Models\Bodega::factory(),
            'codigo' => $this->faker->unique()->bothify('PED-####'),
            'cliente_id' => Cliente::factory(), // crea cliente si no existe
            'fecha' => $this->faker->dateTimeThisYear(),
            'fecha_vencimiento' => $fechaVenc ? $fechaVenc->format('Y-m-d') : null,
            'ciudad' => $this->faker->city(),
            'estado' => $this->faker->randomElement(['PENDIENTE', 'FACTURADO', 'ANULADO']),
            'en_cartera' => $this->faker->boolean(),
            'metodo_pago' => $this->faker->randomElement(['CREDITO', 'CONTADO']),
            'tipo_precio' => $this->faker->randomElement(['FERRETERO', 'MAYORISTA', 'DETAL']),
            'tipo_venta' => $this->faker->randomElement(['ELECTRONICA', 'REMISIONADA']),
            'primer_comentario' => $this->faker->sentence(),
            'segundo_comentario' => $this->faker->optional()->sentence(),
                'subtotal' => 0, // lo calcularemos con los detalles
                'descuento' => 0,
                'abono' => 0,
                'total_a_pagar' => 0,
            'impresa' => $this->faker->boolean(70), // 70% de probabilidad de ser true
            'estado_pago' => $this->faker->randomElement(['EN_CARTERA', 'SALDADO'])

        ];
    }
}
