<?php

namespace App\Filament\Resources\Ingredientes\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class IngredienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('codigo')
                    ->label('Código')
                    ->placeholder('ING-001')
                    ->required()
                    ->maxLength(10)
                    ->unique(ignoreRecord: true),

                TextInput::make('nombre')
                    ->label('Nombre del Imgrediente')
                    ->placeholder('Ejemplo: Tornillo')
                    ->required()
                    ->maxLength(255),

                FileUpload::make('imagen')
                    ->label('Seleccione una imagen')
                    ->image()
                    ->directory('ingredientes')
                    ->disk('public')
                    ->imageEditor()
                    ->downloadable()
                    ->openable()
                    ->nullable()
                    ->maxSize(1024) // 1MB
                    ->default(null),

                TextInput::make('tipo')
                    ->placeholder('Ejemplo: Tornillería')
                    ->label('Tipo de Ingrediente')
                    ->required(),
            ]);
    }
}
