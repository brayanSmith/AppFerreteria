<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use App\Models\Pedido;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Group as ComponentsGroup;
use App\Filament\Resources\PedidoResource\Pages;
use App\Filament\Resources\Pedidos\Pages\CreatePedido;
use App\Filament\Resources\Pedidos\Pages\EditPedido;

class PedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Grid::make(12)->schema([
                Select::make('cliente_id')
                    ->label('Cliente')
                    ->relationship('cliente', 'razon_social')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpan(4),

                DateTimePicker::make('fecha')->label('Fecha')
                    ->default(now())->required()->columnSpan(3),

                Select::make('estado')
                    ->options([
                        'borrador' => 'Borrador',
                        'pagado'   => 'Pagado',
                        'anulado'  => 'Anulado',
                    ])->default('borrador')->columnSpan(3),

                Placeholder::make('subtotal_view')
                    ->label('Subtotal')
                    ->content(
                        fn($record, $get) =>
                        number_format((float)($get('subtotal') ?? $record?->subtotal ?? 0), 2)
                    )


            ]),

            // IZQUIERDA: Catálogo (galería / tabla) con botón "Agregar"
            ComponentsGroup::make()->schema([
                ViewField::make('productPicker')
                    ->view('filament.pos.product-picker') // Blade con galería y botón Agregar
            ->visible(fn ($livewire) =>
                            $livewire instanceof CreatePedido
                            || $livewire instanceof EditPedido
                        ),
                ])->columnSpan(5),

            // DERECHA: Datos del pedido + líneas + totales
            ComponentsGroup::make()->schema([
                Grid::make(12)->schema([
                    Select::make('cliente_id')
                        ->label('Cliente')
                        ->relationship('cliente', 'razon_social')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->columnSpan(4),

                    DateTimePicker::make('fecha')
                        ->label('Fecha')
                        ->default(now())
                        ->required()
                        ->columnSpan(3),

                    Select::make('estado')
                        ->options([
                            'borrador' => 'Borrador',
                            'pagado'   => 'Pagado',
                            'anulado'  => 'Anulado',
                        ])
                        ->default('borrador')
                        ->columnSpan(3),

                    Placeholder::make('subtotal_view')
                        ->label('Subtotal')
                        ->content(fn($record, $get) => number_format(
                            (float)($get('subtotal') ?? $record?->subtotal ?? 0),
                            2
                        ))
                        ->columnSpan(2),
                ]),

                Section::make('Ítems')->schema([
                    Repeater::make('detallePedidos') // relación hasMany en el modelo Pedido
                        ->relationship()
                        ->defaultItems(0)
                        ->live()
                        ->schema([
                            Select::make('producto_id')
                                ->label('Producto')
                                ->relationship('producto', 'nombre_producto', fn($q) => $q->where('activo', true))
                                ->searchable()
                                ->preload()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    if (!$state) return;
                                    $p = \App\Models\Producto::find($state);
                                    if ($p) {
                                        $set('precio_unitario', $p->valor_detal_producto);
                                        $cantidad = (int)($get('cantidad') ?? 1);
                                        $set('subtotal', round($cantidad * $p->valor_detal_producto, 2));
                                    }
                                }),

                            TextInput::make('cantidad')
                                ->numeric()->minValue(1)->default(1)->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $precio = (float)($get('precio_unitario') ?? 0);
                                    $set('subtotal', round($state * $precio, 2));
                                })
                                ->suffix('uds'),

                            TextInput::make('precio_unitario')
                                ->numeric()->minValue(0)
                                ->reactive()->required()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $cantidad = (int)($get('cantidad') ?? 1);
                                    $set('subtotal', round($cantidad * (float)$state, 2));
                                })
                                ->prefix('$'),

                            TextInput::make('subtotal')
                                ->numeric()
                                ->readOnly()
                                ->dehydrated(true)
                                ->prefix('$'),
                        ])
                        ->columns(4),
                ])->collapsed(false),

                Section::make('Totales')->schema([
                    TextInput::make('subtotal')->label('Subtotal')->readOnly()->prefix('$'),
                    TextInput::make('impuestos')->label('Impuestos')->readOnly()->prefix('$'),
                    TextInput::make('total')->label('Total')->readOnly()->prefix('$'),
                ])->columns(3),
            ])->columnSpan(7),

        ]);
    }
}
