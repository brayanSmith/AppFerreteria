<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ruta;

class RutaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Ruta::create([
            'ruta' => 'Norte',
            'descripcion' => 'Ruta del norte de la ciudad',
        ]);
        Ruta::create([
            'ruta' => 'Sur',
            'descripcion' => 'Ruta del sur de la ciudad',
        ]);
        Ruta::create([
            'ruta' => 'Este',
            'descripcion' => 'Ruta del este de la ciudad',
        ]);
        Ruta::create([
            'ruta' => 'Oeste',
            'descripcion' => 'Ruta del oeste de la ciudad',
        ]);
    }
}
