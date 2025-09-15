<?php

namespace App\Filament\Resources\Produccions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProduccionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('formula_id')
                    ->required()
                    ->numeric(),
                TextInput::make('cantidad')
                    ->required()
                    ->numeric(),
                TextInput::make('lote')
                    ->required(),
                DatePicker::make('fecha_produccion')
                    ->required(),
                DatePicker::make('fecha_caducidad')
                    ->required(),
                Textarea::make('Observaciones')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
