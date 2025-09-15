<?php

namespace App\Filament\Resources\Produccions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProduccionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('formula_id')
                    ->numeric(),
                TextEntry::make('cantidad')
                    ->numeric(),
                TextEntry::make('lote'),
                TextEntry::make('fecha_produccion')
                    ->date(),
                TextEntry::make('fecha_caducidad')
                    ->date(),
                TextEntry::make('Observaciones')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
