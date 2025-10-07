<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use App\Models\Producto;


class PedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // 🔹 Datos generales del pedido
            Section::make('Datos del pedido')
                ->columns(4)
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
                        ->columnSpan(3),

                    DatePicker::make('fecha')
                        ->label('Fecha Registro')
                        ->required()
                        ->columnSpan(2),

                    DatePicker::make('fecha_vencimiento')
                        ->label('Fecha de vencimiento')
                        ->default(null)
                        ->columnSpan(2),

                    TextInput::make('ciudad')
                        ->default(null)
                        ->columnSpan(2),

                    Select::make('estado')
                        ->options([
                            'PENDIENTE' => 'Pendiente',
                            'FACTURADO' => 'Facturado',
                            'ANULADO'   => 'Anulado',
                        ])
                        ->default('PENDIENTE')
                        ->required()
                        ->columnSpan(2),

                    Select::make('metodo_pago')
                        ->options([
                            'A CREDITO' => 'A Crédito',
                            'EFECTIVO'  => 'Efectivo',
                        ])
                        ->default('A CREDITO')
                        ->required()
                        ->columnSpan(2),

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
                        ->currencyMask(".", ",", 0)
                        ->prefix('$')
                        ->readOnly()
                        ->numeric(),
                    TextInput::make('abono')
                        ->prefix('$')
                        ->currencyMask(".", ",", 0)
                        ->numeric()
                        ->readOnly(),
                    TextInput::make('descuento')
                        ->prefix('$')
                        ->currencyMask(".", ",", 0)
                        ->numeric()
                        ->live()
                        ->afterStateUpdated(fn($state, $set, $get) => self::recalcularAbonos($set, $get)),

                    TextInput::make('total_a_pagar')
                        ->label('Total a pagar')
                        ->prefix('$')
                        ->currencyMask(".", ",", 0)
                        ->readOnly()
                        ->numeric(),

                ])
                ->columnSpan(1),

            // 🔹 Comentarios
            Section::make('Comentarios')
                ->columnSpanFull()
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
                        ->label(function ($get) {
                            $detalles = $get('detalles') ?? [];
                            $total = collect($detalles)->sum(callback: fn($detalle) => (float) ($detalle['subtotal'] ?? 0));
                            return 'Productos añadidos (Total: $' . number_format($total, 0, ',', '.') . ')';
                        })


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
                                ->currencyMask(".", ",", 0)
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
                                ->currencyMask(".", ",", 0)
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->columnSpan(1),
                        ]),
                ])
                ->afterStateUpdated(function ($set, $get) {
                    self::recalcularTodo($set, $get, $get('tipo_precio'));
                }),

            Section::make('Abonos')
                ->columnSpanFull()
                ->schema([
                    Repeater::make('abonos')
                        ->relationship('abonoPedido')
                        ->label(function ($get) {
                            $abonos = $get('abonos') ?? [];
                            $total = collect($abonos)->sum(fn($abono) => (float) ($abono['monto'] ?? 0));
                            return 'Abonos realizados (Total: $' . number_format($total, 0, ',', '.') . ')';
                        })
                        ->schema([

                            // Columna izquierda: datos
                            Section::make('Datos del abono')
                                ->schema([
                                    DateTimePicker::make('fecha')
                                        ->label('Fecha')
                                        ->required()
                                        ->default(now())
                                        ->columnSpan(1),

                                    TextInput::make('monto')
                                        ->label('Monto')
                                        ->prefix('$')
                                        ->inputMode('decimal')
                                        ->currencyMask(".", ",", 0)
                                        ->required()
                                        //->mask(RawJs::make('$money($input)'))
                                        ->stripCharacters('.')
                                        ->numeric()
                                        ->columnSpan(1),

                                    Select::make('forma_pago')
                                        ->label('Forma de pago')
                                        ->options([
                                            'EFECTIVO'      => 'Efectivo',
                                            'TARJETA'       => 'Tarjeta',
                                            'NEQUI'         => 'Nequi',
                                            'DAVIPLATA'     => 'Daviplata',
                                            'PSE'           => 'PSE',
                                            'TRANSFERENCIA' => 'Transferencia',
                                            'OTRO'          => 'Otro',
                                        ])
                                        ->required()
                                        ->columnSpan(1),

                                    Textarea::make('descripcion')
                                        ->label('Descripción')
                                        ->default(null)
                                        ->columnSpan(2),

                                    Select::make('user_id')
                                        ->label('Usuario que registra')
                                        ->relationship('user', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->columnSpan(1),
                                ])
                                ->columns(3)
                                ->columnSpan(2),

                            // Columna derecha: soporte
                            Section::make('Soporte')
                                ->schema([
                                    FileUpload::make('imagen')
                                        ->label('Comprobante o evidencia')
                                        ->directory('abonos')
                                        ->image()
                                        ->imagePreviewHeight('200')
                                        ->columnSpanFull(),
                                ])
                                ->columnSpan(1),
                        ])
                        ->columns(3)
                        ->columnSpan(4)
                        ->disabled(fn($get) => $get('estado') === 'ANULADO')
                        ->hidden(fn($get) => $get('estado') === 'ANULADO'),
                ])

                ->afterStateUpdated(function ($set, $get) {
                    self::recalcularAbonos($set, $get);
                })

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
            if (! $detalle['producto_id']) continue;

            $producto = Producto::find($detalle['producto_id']);
            if (! $producto) continue;

            $precio = $producto->getPrecioPorTipo($tipoPrecio);
            $cantidad = $detalle['cantidad'] ?? 0;
            $subtotal = $cantidad * $precio;

            $set("detalles.$index.precio_unitario", $precio);
            $set("detalles.$index.subtotal", $subtotal);

            $subtotalGeneral += $subtotal;
        }

        $set('subtotal', $subtotalGeneral);

        // 👇 recalcular abonos y total_a_pagar también
        self::recalcularAbonos($set, $get);
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
    private static function recalcularAbonos(callable $set, callable $get): void
    {
        $abonos = $get('abonos') ?? [];
        $totalAbonos = collect($abonos)->sum(fn($abono) => (float) ($abono['monto'] ?? 0));

        $set('abono', $totalAbonos);

        // 🔹 Recalcular total_a_pagar = subtotal - descuento - abonos
        $subtotal = (float) ($get('subtotal') ?? 0);
        $descuento = (float) ($get('descuento') ?? 0);

        $totalAPagar = $subtotal - $descuento - $totalAbonos;
        $set('total_a_pagar', $totalAPagar);
    }
}
