<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tipo_documento')
                    ->options([
                        'CC' => 'CC',
                        'NIT' => 'NIT',
                        'CE' => 'CE',
                    ])
                    ->required(),
                TextInput::make('numero_documento')
                    ->required(),
                TextInput::make('razon_social')
                    ->required(),
                TextInput::make('direccion')
                    ->required(),
                TextInput::make('telefono')
                    ->tel()
                    ->required(),
                TextInput::make('ciudad')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('representante_legal')
                    ->required(),
                Toggle::make('activo')
                    ->required(),
                Select::make('novedad')
                    ->options([
                        'Nuevo' => 'Nuevo',
                        'Regular' => 'Regular',
                        'Moroso' => 'Moroso',
                    ])
                    ->default(null),
            ]);
    }
}
