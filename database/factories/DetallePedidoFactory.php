<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetallePedido>
 */
class DetallePedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = DetallePedido::class;
    public function definition(): array
    {

            //
             $cantidad = $this->faker->numberBetween(1, 10);
        $precioUnitario = $this->faker->randomFloat(2, 1000, 50000); // precios falsos

        return [
            'pedido_id' => Pedido::factory(), // si no existe, crea pedido
            'producto_id' => Producto::factory(), // si no existe, crea producto
            'cantidad' => $cantidad,
            'precio_unitario' => $precioUnitario,
            'subtotal' => $cantidad * $precioUnitario,
        ];
    }
}
