<?php

namespace App\Filament\Resources\PedidosPendientes\Schemas;

use App\Filament\Resources\Pedidos\Schemas\Concerns\HasPedidoSections;
use Filament\Schemas\Schema;
//use App\Filament\Resources\Pedidos\Schemas\Concerns\HasPedidoSections;

class PedidosPendientesForm
{
    use HasPedidoSections;

    public static function configure(Schema $schema): Schema
    {
        // Componer el schema usando las secciones del trait
        $components = array_merge(
            self::placeholders(),
            self::sectionDatosGenerales(),
            self::sectionResumen(),
            self::sectionComentarios(),
            self::sectionDetalles(),
            self::sectionAbonos()
        );

        return $schema->components($components);
    }
}



