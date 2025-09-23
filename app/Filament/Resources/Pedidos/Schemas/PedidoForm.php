<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;
use App\Models\Producto;


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
                        'PENDIENTE' => 'Pendiente',
                        'FACTURADO' => 'Facturado',
                        'ANULADO' => 'Anulado',
                    ])
                    ->default('PENDIENTE')
                    ->required(),
                Select::make('metodo_pago')
                    ->options([
                        'A CREDITO' => 'A Credito',
                        'EFECTIVO' => 'Efectivo'
                    ])
                    ->default('A CREDITO')
                    ->required(),
                Select::make('tipo_precio')
                    ->options([
                        'FERRETERO' => 'Ferretero',
                        'MAYORISTA' => 'Mayorista',
                        'DETAL' => 'Detall'
                    ])
                    ->default('DETAL')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $detalles = $get('detalles') ?? [];

                        foreach ($detalles as $index => $detalle) {
                            if (empty($detalle['producto_id'])) {
                                continue;
                            }

                            $producto = Producto::find($detalle['producto_id']);
                            if (! $producto) {
                                continue;
                            }

                            // Elegir precio según el tipo
                            $precio = match ($state) {
                                'MAYORISTA' => $producto->valor_mayorista_producto ?? 0,
                                'FERRETERO' => $producto->valor_ferretero_producto ?? 0,
                                default     => $producto->valor_detal_producto ?? 0,
                            };

                            $cantidad = $detalle['cantidad'] ?? 0;
                            $total = $cantidad * $precio;

                            // actualizar fila en repeater
                            $set("detalles.$index.precio_unitario", $precio);
                            $set("detalles.$index.total", $total);
                        }
                        // recalcular subtotal
                        $detalles = $get('detalles') ?? [];
                        $subtotal = collect($detalles)->sum(fn($d) => $d['total'] ?? 0);
                        $set('subtotal', $subtotal);
                    }),
                Textarea::make('primer_comentario')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('segundo_comentario')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->disabled()        // 👈 para que no lo editen a mano
                    ->dehydrated(true), // 👈 se guarda en BD aunque esté disabled


                Repeater::make('detalles')
                    ->relationship('detalles') // 👈 relación en el modelo Pedido
                    ->label('Detalles del pedido')
                    ->schema([
                        Select::make('producto_id')
                            ->label('Producto')
                            ->relationship('producto', 'nombre_producto')
                            ->searchable()
                            ->required()
                            ->preload()
                            ->live()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                if (! $state) {
                                    $set('precio_unitario', 0);
                                    $set('total', 0);
                                    return;
                                }

                                // Buscar el producto en DB
                                $producto = Producto::find($state);
                                if (! $producto) {
                                    return;
                                }

                                // 👇 Obtenemos el tipo de precio desde el formulario padre
                                $tipoPrecio = $get('../../tipo_precio') ?? 'DETAL';

                                $precio = match ($tipoPrecio) {
                                    'MAYORISTA'  => $producto->valor_mayorista_producto ?? 0,
                                    'FERRETERO'  => $producto->valor_ferretero_producto ?? 0,
                                    default      => $producto->valor_detal_producto ?? 0,
                                };

                                $set('precio_unitario', $precio);
                                $set('total', ($get('cantidad') ?: 0) * $precio);

                                // 👇 recalcular subtotal
                                $detalles = $get('../../detalles') ?? [];
                                $subtotal = collect($detalles)->sum(fn($d) => $d['total'] ?? 0);
                                $set('../../subtotal', $subtotal);
                            }),

                        TextInput::make('cantidad')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $total = ($state ?: 0) * ($get('precio_unitario') ?: 0);
                                $set('total', $total);

                                // 👇 recalcular subtotal
                                $detalles = $get('../../detalles') ?? [];
                                $subtotal = collect($detalles)->sum(fn($d) => $d['total'] ?? 0);
                                $set('../../subtotal', $subtotal);
                            })
                            ->afterStateHydrated(function ($state, callable $set, $get) {
                                // 👇 al hidratar, calcular total inicial
                                $total = ($state ?: 0) * ($get('precio_unitario') ?: 0);
                                $set('total', $total);
                            }),

                        TextInput::make('precio_unitario')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $total = ($get('cantidad') ?: 0) * ($state ?: 0);
                                $set('total', $total);

                                // 👇 recalcular subtotal
                                $detalles = $get('../../detalles') ?? [];
                                $subtotal = collect($detalles)->sum(fn($d) => $d['total'] ?? 0);
                                $set('../../subtotal', $subtotal);
                            })
                            ->afterStateHydrated(function ($state, callable $set, $get) {
                                // 👇 al hidratar, calcular total inicial
                                $total = ($get('cantidad') ?: 0) * ($state ?: 0);
                                $set('total', $total);
                            }),

                        TextInput::make('total')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(true)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $detalles = $get('../../detalles') ?? [];
                                $subtotal = collect($detalles)->sum(fn($d) => $d['total'] ?? 0);
                                $set('../../subtotal', $subtotal);
                            }),
                    ])
                    ->defaultItems(1) // empieza con un ítem por defecto
                    ->minItems(1)
                    ->columns(4)
                    ->collapsible()
                    ->itemLabel(function (array $state): ?string {
                        $cantidad = $state['cantidad'] ?? 0;
                        $precio = $state['precio_unitario'] ?? 0;
                        $total  = $state['total'] ?? ($cantidad * $precio);

                        // si tenemos el producto id, intentamos obtener nombre (si no, mostramos texto genérico)
                        $nombre = 'Sin producto';
                        if (! empty($state['producto_id'])) {
                            $p = Producto::find($state['producto_id']);
                            $nombre = $p ? ($p->nombre_producto ?? $p->id) : 'Producto #' . $state['producto_id'];
                        }

                        return "{$cantidad} x {$nombre} | {$precio} = {$total}";
                    })
            ]);
    }
}
