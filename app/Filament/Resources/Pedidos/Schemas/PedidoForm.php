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
use Filament\Schemas\Components\Concerns\Cloneable;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use App\Models\Producto;
use DragonCode\Support\Facades\Helpers\Arr;
use Filament\Forms\Components\Actions\Action;
use Carbon\Carbon;
use Filament\Forms\Components\Placeholder;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Hamcrest\Core\IsEqual;

use function Livewire\Volt\on;

class PedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // mostrar días hasta vencimiento solo cuando estado = PENDIENTE
            Placeholder::make('vencimiento_info')
                ->content(function ($get) {
                    $fechaVenc = $get('fecha_vencimiento');
                    if (empty($fechaVenc)) {
                        return '';
                    }

                    try {
                        $hoy = Carbon::today();
                        $venc = Carbon::parse($fechaVenc)->startOfDay();
                        $dias = $hoy->diffInDays($venc, false);

                        if ($dias > 0) {
                            return "Quedan {$dias} día" . ($dias === 1 ? '' : 's') . " para vencerse";
                        }

                        if ($dias === 0) {
                            return 'Vence hoy';
                        }

                        return "Vencido hace " . abs($dias) . " día" . (abs($dias) === 1 ? '' : 's');
                    } catch (\Throwable $e) {
                        return '';
                    }
                })
                ->extraAttributes(function ($get) {
                    $fechaVenc = $get('fecha_vencimiento');
                    if (empty($fechaVenc)) {
                        return ['class' => 'text-sm mb-2'];
                    }

                    try {
                        $hoy = Carbon::today();
                        $venc = Carbon::parse($fechaVenc)->startOfDay();
                        $dias = $hoy->diffInDays($venc, false);

                        if ($dias > 3) {
                            $class = 'text-sm bg-green-600 text-green-50 mb-2 p-2 rounded';
                        } elseif ($dias >= 1) {
                            $class = 'text-sm bg-yellow-600 text-yellow-50 mb-2 p-2 rounded';
                        } elseif ($dias === 0) {
                            $class = 'text-sm bg-yellow-600 text-yellow-50 mb-2 p-2 rounded';
                        } else {
                            $class = 'text-sm bg-red-600 text-red-50 mb-2 p-2 rounded';
                        }

                        return ['class' => $class];
                    } catch (\Throwable $e) {
                        return ['class' => 'text-sm mb-2'];
                    }
                })
                ->visible(fn($get) => $get('estado') === 'PENDIENTE' && ! empty($get('fecha_vencimiento')))
                ->columnSpanFull(),


            // nuevo: letrero "Próximo abono" (usa la última fecha de abono + 30 días)
            Placeholder::make('proximo_abono')
                ->content(function ($get) {
                    $abonos = $get('abonos') ?? [];
                    if (empty($abonos)) {
                        return '';
                    }

                    try {
                        $last = collect($abonos)
                            ->pluck('fecha')
                            ->filter()
                            ->map(fn($f) => Carbon::parse($f))
                            ->sort()
                            ->last();

                        if (! $last) {
                            return '';
                        }

                        $proximo = $last->copy()->addDays(30);
                        $dias = (int) Carbon::today()->diffInDays($proximo, false); // <-- entero
                        $label = $proximo->format('d/m/Y');

                        if ($dias > 0) {
                            return "Próximo abono: {$label} (en {$dias} día" . ($dias === 1 ? '' : 's') . ")";
                        }

                        if ($dias === 0) {
                            return "Próximo abono: {$label} (hoy)";
                        }

                        $vencidos = abs($dias);
                        return "Próximo abono: {$label} (vencido hace {$vencidos} día" . ($vencidos === 1 ? '' : 's') . ")";
                    } catch (\Throwable $e) {
                        return '';
                    }
                })
                ->extraAttributes(function ($get) {
                    $abonos = $get('abonos') ?? [];
                    if (empty($abonos)) {
                        return ['class' => 'text-sm mb-2'];
                    }

                    try {
                        $last = collect($abonos)
                            ->pluck('fecha')
                            ->filter()
                            ->map(fn($f) => Carbon::parse($f))
                            ->sort()
                            ->last();

                        if (! $last) {
                            return ['class' => 'text-sm mb-2'];
                        }

                        $proximo = $last->copy()->addDays(30);
                        $dias = (int) Carbon::today()->diffInDays($proximo, false); // <-- entero

                        if ($dias > 7) {
                            return ['class' => 'text-sm bg-green-600 text-green-50 mb-2 p-2 rounded'];
                        }

                        if ($dias >= 1) {
                            return ['class' => 'text-sm bg-yellow-600 text-yellow-50 mb-2 p-2 rounded'];
                        }

                        return ['class' => 'text-sm bg-red-600 text-red-50 mb-2 p-2 rounded'];
                    } catch (\Throwable $e) {
                        return ['class' => 'text-sm mb-2'];
                    }
                })
                ->visible(fn($get) => ! empty($get('abonos')) && ((float) ($get('total_a_pagar') ?? 0) > 0))
                ->columnSpanFull(),


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
                            'CREDITO' => 'Crédito',
                            'CONTADO'  => 'Contado',
                        ])
                        ->default('CREDITO')
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
                        ->columnSpan(2),

                    ToggleButtons::make('estado_pago')
                        ->options([
                            'EN_CARTERA' => 'En Cartera',
                            'SALDADO'    => 'Saldado',
                        ])
                        ->default('EN_CARTERA')
                        ->grouped()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(
                            fn($state, $set, $get) =>
                            self::recalcularTodo($set, $get, $state)
                        )
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
                        ->readOnly()
                        ->numeric(),
                    TextInput::make('descuento')
                        ->prefix('$')
                        ->currencyMask(".", ",", 0)
                        ->numeric()
                        ->live(onBlur: true)
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
                        // <-- agregado: normalizar/calc antes de guardar (create/update)
                        ->mutateRelationshipDataBeforeSaveUsing(function (array $data, $record = null): array {
                            // asegurar tipos correctos
                            $data['producto_id'] = isset($data['producto_id']) ? (int) $data['producto_id'] : null;
                            $data['cantidad'] = isset($data['cantidad']) ? (float) $data['cantidad'] : 0;
                            $data['precio_unitario'] = isset($data['precio_unitario']) ? (float) $data['precio_unitario'] : 0;

                            // calcular subtotal
                            $data['subtotal'] = $data['cantidad'] * $data['precio_unitario'];

                            // remover claves temporales si existen
                            if (isset($data['_remove_temp'])) {
                                unset($data['_remove_temp']);
                            }

                            return $data;
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
                                ->live(onBlur: true)
                                ->afterStateUpdated(
                                    fn($state, $set, $get) =>
                                    self::recalcularFila($set, $get, $get('../../tipo_precio')),

                                )
                                ->columnSpan(1),

                            TextInput::make('precio_unitario')
                                ->prefix('$')
                                ->currencyMask(".", ",", 0)
                                ->numeric()
                                ->default(0)
                                ->required()
                                ->live(onBlur: true)
                                ->readOnly(true)
                                ->columnSpan(1),

                            TextInput::make('subtotal')
                                ->prefix('$')
                                ->currencyMask(".", ",", 0)
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->columnSpan(1),
                        ])
                        ->deleteAction(
                            fn(\Filament\Actions\Action $action) => $action
                                // usar ->after para ejecutar la lógica después de la eliminación (no sustituye la eliminación)
                                ->after(function ($record, $set, $get) {
                                    // recalcula totales con el estado ya actualizado
                                    self::recalcularTodo($set, $get, $get('tipo_precio'));
                                })
                        ),

                ]),

            Section::make('Abonos')
                ->columnSpanFull()
                ->visible(fn($get) => $get('estado') === 'FACTURADO')
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
                                        ->live(onBlur: true)
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

        // ← agregado: recalcular abonos y total_a_pagar
        self::recalcularAbonos($set, $get);
    }
    /**
     * 🔹 Recalcular abonos y total_a_pagar.
     *
     * Lógica: total_a_pagar = subtotal - descuento - abonos
     */
    private static function recalcularAbonos(callable $set, callable $get): void
    {
        // buscar el scope correcto donde estén los campos (root, padre, abuelo...)
        $paths = ['', '../../', '../../../'];
        $basePath = null;
        foreach ($paths as $p) {
            $maybe = $get($p . 'abonos');
            if (!is_null($maybe)) {
                $basePath = $p;
                break;
            }
        }
        if ($basePath === null) {
            $basePath = '';
        }

        $abonos = $get($basePath . 'abonos') ?? [];
        $totalAbonos = collect($abonos)->sum(fn($abono) => (float) ($abono['monto'] ?? 0));

        // actualizar 'abono' solo si cambia
        $currentAbono = (float) ($get($basePath . 'abono') ?? 0);
        if (round($currentAbono, 4) !== round($totalAbonos, 4)) {
            $set($basePath . 'abono', $totalAbonos);
        }

        // leer subtotal y descuento desde el mismo scope
        $subtotal = (float) ($get($basePath . 'subtotal') ?? 0);
        $descuento = (float) ($get($basePath . 'descuento') ?? 0);

        // total_a_pagar = Subtotal - Abonos - Descuento
        $totalAPagar = $subtotal - $totalAbonos - $descuento;
        $totalAPagar = $totalAPagar < 0 ? 0 : $totalAPagar;

        $currentTotal = (float) ($get($basePath . 'total_a_pagar') ?? 0);
        if (round($currentTotal, 4) !== round($totalAPagar, 4)) {
            $set($basePath . 'total_a_pagar', $totalAPagar);
        }
    }
}
