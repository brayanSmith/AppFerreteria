<?php

namespace App\Filament\Resources\ComprasEstadoPagados\Schemas;

use App\Filament\Resources\Compras\Schemas\Concerns\HasCompraSections;
use Filament\Schemas\Schema;

class ComprasEstadoPagadoForm
{
    use HasCompraSections;
    public static function configure(Schema $schema): Schema
    {

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
