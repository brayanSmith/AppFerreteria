<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('codigo')
                    ->default(null),
                TextInput::make('cliente_id')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('fecha')
                    ->required(),
                TextInput::make('ciudad')
                    ->default(null),
                Select::make('estado')
                    ->options([
            'PENDIENTE' => 'P e n d i e n t e',
            'FACTURADO' => 'F a c t u r a d o',
            'ANULADO' => 'A n u l a d o',
        ])
                    ->default('PENDIENTE')
                    ->required(),
                Select::make('metodo_pago')
                    ->options(['A CREDITO' => 'A c r e d i t o', 'EFECTIVO' => 'E f e c t i v o'])
                    ->default('A CREDITO')
                    ->required(),
                Select::make('tipo_precio')
                    ->options(['FERRETERO' => 'F e r r e t e r o', 'MAYORISTA' => 'M a y o r i s t a', 'DETAL' => 'D e t a l'])
                    ->default('DETAL')
                    ->required(),
                Textarea::make('primer_comentario')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('segundo_comentario')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
