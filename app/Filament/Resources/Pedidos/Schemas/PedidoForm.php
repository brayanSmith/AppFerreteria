<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\ToggleButtons;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\Producto;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\RawJs;

class PedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // 🔹 Datos generales del pedido
            Section::make('Datos del pedido')
                ->columns(3)
                ->schema([
                    TextInput::make('codigo')
                        ->disabled()
                        ->columnSpan(1),

                    Select::make('cliente_id')
                        ->label('Cliente')
                        ->relationship('cliente', 'razon_social')
                        ->searchable()
                        ->required()
                        ->preload()
                        ->columnSpan(2),

                    DateTimePicker::make('fecha')
                        ->required()
                        ->columnSpan(1),

                    TextInput::make('ciudad')
                        ->default(null)
                        ->columnSpan(1),

                    Select::make('estado')
                        ->options([
                            'PENDIENTE' => 'Pendiente',
                            'FACTURADO' => 'Facturado',
                            'ANULADO'   => 'Anulado',
                        ])
                        ->default('PENDIENTE')
                        ->required()
                        ->columnSpan(1),

                    Select::make('metodo_pago')
                        ->options([
                            'A CREDITO' => 'A Crédito',
                            'EFECTIVO'  => 'Efectivo',
                        ])
                        ->default('A CREDITO')
                        ->required()
                        ->columnSpan(1),

                    ToggleButtons::make('tipo_precio')
                        ->options([
                            'FERRETERO' => 'Ferretero',
                            'MAYORISTA' => 'Mayorista',
                            'DETAL'     => 'Detal',
                        ])
                        ->default('DETAL')
                        ->grouped()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(
                            fn($state, $set, $get) =>
                            self::recalcularTodo($set, $get, $state)
                        )
                        ->columnSpan(1),
                ]),

            // 🔹 Totales
            Section::make('Resumen')
                ->schema([
                    TextInput::make('subtotal')
                        ->prefix('$')
                        ->inputMode('decimal')
                        ->readOnly()
                        ->mask(RawJs::make('$money($input)'))
                        ->stripCharacters(',')
                        ->numeric(),
                ])
                ->columnSpan(1),

            // 🔹 Comentarios
            Section::make('Comentarios')
                ->collapsed()
                ->schema([
                    Textarea::make('primer_comentario')
                        ->label('Comentario inicial')
                        ->default(null),

                    Textarea::make('segundo_comentario')
                        ->label('Comentario adicional')
                        ->default(null),
                ]),

            // 🚨 Detalles del pedido (ocupa ancho completo)
            Section::make('Detalles del pedido')
            ->columnSpanFull() // 👈 ocupa toda la fila, sin compartir espacio
                ->schema([
                    Repeater::make('detalles')
                        ->relationship('detalles')
                        ->label('Productos')
                        ->table([
                            // Define the columns for the table
                            TableColumn::make('Producto')
                                ->markAsRequired()
                                ->width('200px'),
                            TableColumn::make('Cantidad')
                                ->markAsRequired()
                                ->width('100px'),
                            TableColumn::make('Precio Unitario')
                                ->markAsRequired()
                                ->width('100px'),
                            TableColumn::make('Subtotal')
                                ->markAsRequired()
                                ->width('100px'),
                            TableColumn::make('Acciones')
                            ->width('10px'),
                        ])

                        ->schema([
                            Select::make('producto_id')
                                ->label('Producto')
                                ->relationship('producto', 'nombre_producto')
                                ->searchable()
                                ->required()
                                ->preload()
                                ->reactive()
                                ->afterStateUpdated(
                                    fn($state, $set, $get) =>
                                    self::recalcularFila($set, $get, $get('../../tipo_precio'))
                                )
                                ->columnSpan(2),

                            TextInput::make('cantidad')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(
                                    fn($state, $set, $get) =>
                                    self::recalcularFila($set, $get, $get('../../tipo_precio'))
                                )
                                ->columnSpan(1),

                            TextInput::make('precio_unitario')
                                ->prefix('$')
                                ->numeric()
                                ->default(0)
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(
                                    fn($state, $set, $get) =>
                                    self::recalcularFila($set, $get, $get('../../tipo_precio'))
                                )
                                ->columnSpan(1),

                            TextInput::make('subtotal')
                                ->prefix('$')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->columnSpan(1),
                        ]),
                ])

                ->afterStateUpdated(function ($set, $get) {
                    self::recalcularTodo($set, $get, $get('tipo_precio'));
                }),
            ]);
    }


    /**
     * 🔹 Recalcular toda la tabla (cuando cambia tipo_precio).
     */
    private static function recalcularTodo(callable $set, callable $get, string $tipoPrecio): void
    {
        $detalles = $get('detalles') ?? [];
        $subtotalGeneral = 0;

        foreach ($detalles as $index => $detalle) {
            if (! $detalle['producto_id']) {
                continue;
            }

            $producto = Producto::find($detalle['producto_id']);
            if (! $producto) {
                continue;
            }

            $precio = $producto->getPrecioPorTipo($tipoPrecio);
            $cantidad = $detalle['cantidad'] ?? 0;
            $subtotal = $cantidad * $precio;

            $set("detalles.$index.precio_unitario", $precio);
            $set("detalles.$index.subtotal", $subtotal);

            $subtotalGeneral += $subtotal;
        }

        $set('subtotal', $subtotalGeneral);
    }

    /**
     * 🔹 Recalcular una fila (producto, cantidad o precio).
     */
    private static function recalcularFila(callable $set, callable $get, string $tipoPrecio): void
    {
        $productoId = $get('producto_id');
        $cantidad   = $get('cantidad') ?? 0;
        $precio     = $get('precio_unitario') ?? 0;

        if ($productoId) {
            $producto = Producto::find($productoId);
            if ($producto) {
                $precio = $producto->getPrecioPorTipo($tipoPrecio);
                $set('precio_unitario', $precio);
            }
        }

        $subtotal = $cantidad * $precio;
        $set('subtotal', $subtotal);

        // 🔹 Recalcular total general
        $detalles = $get('../../detalles') ?? [];
        $totalPedido = collect($detalles)->sum(fn($d) => $d['subtotal'] ?? 0);
        $set('../../subtotal', $totalPedido);
    }

    /**
     * 🔹 Etiqueta de la fila colapsada.
     */
    private static function filaLabel(array $state): string
    {
        $cantidad = $state['cantidad'] ?? 0;
        $precio   = $state['precio_unitario'] ?? 0;
        $total    = $state['subtotal'] ?? ($cantidad * $precio); // por si no se ha calculado aún

        $nombre = 'Sin producto';
        if (! empty($state['producto_id'])) {
            $p = Producto::find($state['producto_id']);
            $nombre = $p ? $p->nombre_producto : "Producto #{$state['producto_id']}";
        }

        return "{$cantidad} x {$nombre} | {$precio} = {$total}";
    }
}
