<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use Filament\Schemas\Schema;
use App\Filament\Resources\Pedidos\Schemas\Concerns\HasPedidoSections;

class PedidoForm
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
