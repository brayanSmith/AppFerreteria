<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
      // Obtener IDs de las categorías existentes
        $ferreteriaId = DB::table('categorias')->where('nombre_categoria', 'Ferretería')->value('id');
        $construccionId = DB::table('categorias')->where('nombre_categoria', 'Construcción')->value('id');
        $pinturasId = DB::table('categorias')->where('nombre_categoria', 'Pinturas')->value('id');

        DB::table('sub_categorias')->insert([
            [
                'nombre_sub_categoria' => 'Herramientas Manuales',
                'categoria_id' => $ferreteriaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_sub_categoria' => 'Cemento y Concreto',
                'categoria_id' => $construccionId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_sub_categoria' => 'Esmaltes',
                'categoria_id' => $pinturasId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_sub_categoria' => 'Arcillas y Adhesivos',
                'categoria_id' => $ferreteriaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_sub_categoria' => 'Ladrillos y Bloques',
                'categoria_id' => $construccionId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_sub_categoria' => 'Barnices',
                'categoria_id' => $pinturasId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
