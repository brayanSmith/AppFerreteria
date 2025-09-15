<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('categorias')->insert([
            [
                'nombre_categoria' => 'Ferretería',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_categoria' => 'Construcción',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_categoria' => 'Pinturas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
