<?php

namespace App\Filament\Resources\PedidosFacturados\Schemas;
use App\Filament\Resources\Pedidos\Schemas\Concerns\HasPedidoSections;

use Filament\Schemas\Schema;

class PedidosFacturadosForm
{
    use HasPedidoSections;

    public static function configure(Schema $schema): Schema
    {
        // Componer el schema usando las secciones del trait
        $components = array_merge(
            self::placeholders(),
            self::sectionDatosGenerales(),
            self::sectionResumen(),
            self::sectionDetalles(),
            self::sectionAbonos()
        );

        return $schema->components($components);
    }
}
