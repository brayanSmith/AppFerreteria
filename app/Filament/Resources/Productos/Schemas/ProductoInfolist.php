<?php

namespace App\Filament\Resources\Productos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('codigo_producto'),
                TextEntry::make('nombre_producto'),
                TextEntry::make('descripcion_producto')
                    ->placeholder('-'),
                TextEntry::make('categoria_producto'),
                TextEntry::make('sub_categoria_producto'),
                TextEntry::make('costo_producto')
                    ->numeric(),
                TextEntry::make('valor_detal_producto')
                    ->numeric(),
                TextEntry::make('valor_mayorista_producto')
                    ->numeric(),
                TextEntry::make('valor_ferretero_producto')
                    ->numeric(),
                TextEntry::make('imagen_producto')
                    ->placeholder('-'),
                TextEntry::make('bodega_id')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
