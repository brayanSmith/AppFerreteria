<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SubCategoria;
use App\Models\Bodega;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoria = Categoria::inRandomOrder()->first();
        $subCategoria = $categoria
            ? SubCategoria::where('categoria_id', $categoria->id)->inRandomOrder()->first()
            : null;

        return [
            'codigo_producto' => strtoupper(Str::random(8)),
            'nombre_producto' => $this->faker->words(3, true),
            'descripcion_producto' => $this->faker->sentence(),
            'costo_producto' => $this->faker->randomFloat(2, 5000, 50000),
            'valor_detal_producto' => $this->faker->randomFloat(2, 6000, 60000),
            'valor_mayorista_producto' => $this->faker->randomFloat(2, 5500, 55000),
            'valor_ferretero_producto' => $this->faker->randomFloat(2, 5200, 52000),
            'imagen_producto' => null, // o $this->faker->imageUrl(640, 480, 'products', true)
            'bodega_id' => Bodega::inRandomOrder()->value('id'),
            'categoria_id' => $categoria?->id,
            'sub_categoria_id' => $subCategoria?->id,
            'stock' => $this->faker->numberBetween(0, 100),
            'activo' => $this->faker->boolean(90), // 90% de probabilidad de estar activo
        ];
    }
}
