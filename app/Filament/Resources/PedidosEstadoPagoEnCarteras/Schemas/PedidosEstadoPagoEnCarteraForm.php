<?php

namespace App\Filament\Resources\PedidosEstadoPagoEnCarteras\Schemas;
use App\Filament\Resources\Pedidos\Schemas\Concerns\HasPedidoSections;

use Filament\Schemas\Schema;

class PedidosEstadoPagoEnCarteraForm
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
