<?php

namespace App\Filament\Resources\Ingredientes\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\ImageColumn;
use Filament\Schemas\Schema;

class IngredienteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('codigo'),
                TextEntry::make('nombre'),

                ImageEntry::make('imagen')
                    ->label('Imagen')
                    ->placeholder('-')
                    ->disk('public'),
                    //->size(100),


                TextEntry::make('tipo'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
