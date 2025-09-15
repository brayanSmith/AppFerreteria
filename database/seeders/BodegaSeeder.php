<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BodegaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bodegas')->insert([
            [
                'nombre_bodega' => 'Bodega Central',
                'ubicacion_bodega' => 'Bogotá',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_bodega' => 'Bodega Norte',
                'ubicacion_bodega' => 'Medellín',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_bodega' => 'Bodega Sur',
                'ubicacion_bodega' => 'Cali',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
