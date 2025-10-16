<?php

namespace App\Filament\Resources\Pedidos\Schemas\Concerns;

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
use Filament\Forms\Components\Placeholder;
use Carbon\Carbon;
use App\Models\Producto;
use Dom\Text;
use Filament\Forms\Components\Toggle;
use App\Models\Cliente;

use function Livewire\Volt\on;

trait HasPedidoSections
{
    // placeholders informativos (vencimiento / proximo abono)
    protected static function placeholders()
    {

        return [
            Placeholder::make('vencimiento_info')
                ->content(function ($get) {
                    $fechaVenc = $get('fecha_vencimiento');
                    if (empty($fechaVenc)) return '';
                    try {
                        $hoy = Carbon::today();
                        $venc = Carbon::parse($fechaVenc)->startOfDay();
                        $dias = $hoy->diffInDays($venc, false);
                        if ($dias > 0) return "Quedan {$dias} día" . ($dias === 1 ? '' : 's') . " para vencerse";
                        if ($dias === 0) return 'Vence hoy';
                        return "Vencido hace " . abs($dias) . " día" . (abs($dias) === 1 ? '' : 's');
                    } catch (\Throwable $e) {
                        return '';
                    }
                })
                ->extraAttributes(function ($get) {
                    $fechaVenc = $get('fecha_vencimiento');
                    if (empty($fechaVenc)) return ['class' => 'text-sm mb-2'];
                    try {
                        $hoy = Carbon::today();
                        $venc = Carbon::parse($fechaVenc)->startOfDay();
                        $dias = $hoy->diffInDays($venc, false);
                        if ($dias > 3) $class = 'text-sm bg-green-600 text-green-50 mb-2 p-2 rounded';
                        elseif ($dias >= 1) $class = 'text-sm bg-yellow-600 text-yellow-50 mb-2 p-2 rounded';
                        elseif ($dias === 0) $class = 'text-sm bg-yellow-600 text-yellow-50 mb-2 p-2 rounded';
                        else $class = 'text-sm bg-red-600 text-red-50 mb-2 p-2 rounded';
                        return ['class' => $class];
                    } catch (\Throwable $e) {
                        return ['class' => 'text-sm mb-2'];
                    }
                })
                ->visible(fn($get) => $get('estado') === 'PENDIENTE' && ! empty($get('fecha_vencimiento')))
                ->columnSpanFull(),

            Placeholder::make('proximo_abono')
                ->content(function ($get) {
                    $abonos = $get('abonos') ?? [];
                    if (empty($abonos)) return '';
                    try {
                        $last = collect($abonos)->pluck('fecha')->filter()->map(fn($f) => Carbon::parse($f))->sort()->last();
                        if (! $last) return '';
                        $proximo = $last->copy()->addDays(30);
                        $dias = (int) Carbon::today()->diffInDays($proximo, false);
                        $label = $proximo->format('d/m/Y');
                        if ($dias > 0) return "Próximo abono: {$label} (en {$dias} día" . ($dias === 1 ? '' : 's') . ")";
                        if ($dias === 0) return "Próximo abono: {$label} (hoy)";
                        $vencidos = abs($dias);
                        return "Próximo abono: {$label} (vencido hace {$vencidos} día" . ($vencidos === 1 ? '' : 's') . ")";
                    } catch (\Throwable $e) {
                        return '';
                    }
                })
                ->extraAttributes(function ($get) {
                    $abonos = $get('abonos') ?? [];
                    if (empty($abonos)) return ['class' => 'text-sm mb-2'];
                    try {
                        $last = collect($abonos)->pluck('fecha')->filter()->map(fn($f) => Carbon::parse($f))->sort()->last();
                        if (! $last) return ['class' => 'text-sm mb-2'];
                        $proximo = $last->copy()->addDays(30);
                        $dias = (int) Carbon::today()->diffInDays($proximo, false);
                        if ($dias > 7) return ['class' => 'text-sm bg-green-600 text-green-50 mb-2 p-2 rounded'];
                        if ($dias >= 1) return ['class' => 'text-sm bg-yellow-600 text-yellow-50 mb-2 p-2 rounded'];
                        return ['class' => 'text-sm bg-red-600 text-red-50 mb-2 p-2 rounded'];
                    } catch (\Throwable $e) {
                        return ['class' => 'text-sm mb-2'];
                    }
                })
                ->visible(fn($get) => ! empty($get('abonos')) && ((float) ($get('total_a_pagar') ?? 0) > 0))
                ->columnSpanFull(),
        ];
    }

    // sección datos generales
    protected static function sectionDatosGenerales(): array
    {
        return [
            Section::make('Datos del pedido')
                ->columns(4)
                ->columnSpan(1)
                ->schema([
                    TextInput::make('codigo')->disabled()->columnSpan(1)->label('Remisión'),

                    Select::make('cliente_id')
                        ->label('Cliente')
                        ->relationship('cliente', 'razon_social')
                        ->searchable()
                        ->required()
                        ->preload()
                        ->reactive()
                        ->afterStateHydrated(fn($state, $set) => $set('retenedor_fuente_flag', $state ? (Cliente::find($state)?->retenedor_fuente === 'SI') : false))
                        ->afterStateUpdated(function ($state, $set) {
                            $set('retenedor_fuente_flag', $state ? (Cliente::find($state)?->retenedor_fuente === 'SI') : false);
                        })
                        ->columnSpan(3),

                    DatePicker::make('fecha')->label('Fecha de Facturación')->required()->columnSpan(2),

                    TextInput::make('ciudad')->default(null)->columnSpan(2),

                    TextInput::make('dias_plazo_vencimiento')->label('Días Plazo Vencimiento')->default(30)->numeric()->required()->reactive()
                        ->afterStateUpdated(function ($state, $set, $get) {
                            $fecha = $get('fecha');
                            if ($fecha && $state !== null) {
                                try {
                                    $fechaCarbon = Carbon::parse($fecha);
                                    $nuevaFechaVenc = $fechaCarbon->copy()->addDays((int) $state);
                                    $set('fecha_vencimiento', $nuevaFechaVenc->toDateString());
                                } catch (\Throwable $e) {
                                    // no hacer nada si hay error
                                }
                            } else {
                                $set('fecha_vencimiento', null);
                            }
                        })
                        ->minValue(0)
                        ->maxValue(365)
                        ->step(1)
                        //->helperText('Número de días para calcular la fecha de vencimiento a partir de la fecha de facturación.')
                        ->columnSpan(2),

                    Select::make('metodo_pago')->options(['CREDITO' => 'Crédito', 'CONTADO' => 'Contado'])->default('CREDITO')->required()->columnSpan(2),

                    DatePicker::make('fecha_vencimiento')->label('Fecha de Vencimiento')->default(null)->columnSpan(2)->readOnly(),

                    ToggleButtons::make('tipo_precio')
                        ->options(['FERRETERO' => 'Ferretero', 'MAYORISTA' => 'Mayorista', 'DETAL' => 'Detal'])
                        ->default('DETAL')
                        ->grouped()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn($state, $set, $get) => self::recalcularTodo($set, $get, $state))
                        ->columnSpan(2),

                    // Toggle informativo (SI / NO). No se persiste en la BD.
                    Toggle::make('retenedor_fuente_flag')
                        ->label('Retenedor fuente')
                        ->disabled()               // solo informativo: el usuario no lo cambia aquí
                        ->dehydrated(false)        // no guardarlo en la BD
                        ->columnSpan(2),


                    ToggleButtons::make('estado')->options([
                        'PENDIENTE' => 'Pendiente',
                        'FACTURADO' => 'Facturado',
                        'ANULADO'   => 'Anulado',
                    ])->default('PENDIENTE')->required()->columnSpan(2)->grouped(),

                    ToggleButtons::make('tipo_venta')->options([
                        'REMISIONADA' => 'Remisionada',
                        'ELECTRONICA' => 'Electrónica',
                    ])->required()->columnSpan(2)->grouped(),


                    // El estado de pago ahora se controla automáticamente al guardar (no editable manualmente aquí)
                    /*Placeholder::make('estado_pago_info')
                        ->label('Estado pago')
                        ->content(fn($get) => $get('estado_pago') ?? 'EN_CARTERA')
                        ->extraAttributes(['class' => 'text-sm text-gray-600'])
                        ->columnSpan(2),*/
                ]),
        ];
    }

    // sección resumen
    protected static function sectionResumen(): array
    {
        return [
            Section::make('Resumen')
                ->schema([
                    TextInput::make('subtotal')->currencyMask(".", ",", 0)->prefix('$')->readOnly()->numeric(),
                    TextInput::make('abono')->prefix('$')->currencyMask(".", ",", 0)->readOnly()->numeric(),
                    TextInput::make('descuento')->prefix('$')->currencyMask(".", ",", 0)->numeric()->live(onBlur: true)->afterStateUpdated(fn($state, $set, $get) => self::recalcularAbonos($set, $get)),
                    TextInput::make('total_a_pagar')->label('Total a pagar')->prefix('$')->currencyMask(".", ",", 0)->readOnly()->numeric(),
                ])->columnSpan(1),
        ];
    }

    //seccion primer comentario y segundo comentario
    protected static function sectionComentarios(): array
    {
        return [
            Section::make('Comentarios')
                ->columns(1)
                ->schema([
                    Textarea::make('primer_comentario')->label('Primer Comentario')->rows(2)->columnSpanFull(),
                    Textarea::make('segundo_comentario')->label('Segundo Comentario')->rows(2)->columnSpanFull(),
                ])->columnSpanFull()->collapsed(true)->collapsible(),
        ];
    }

    // sección detalles
    protected static function sectionDetalles(): array
    {
        return [
            Section::make('Detalles del pedido')
                ->columnSpanFull()
                ->schema([
                    Repeater::make('detalles')
                        ->relationship('detalles')
                        ->label(function ($get) {
                            $detalles = $get('detalles') ?? [];
                            $total = collect($detalles)->sum(callback: fn($detalle) => (float) ($detalle['subtotal'] ?? 0));
                            return 'Productos añadidos (Total: $' . number_format($total, 0, ',', '.') . ')';
                        })
                        ->mutateRelationshipDataBeforeSaveUsing(function (array $data, $record = null): array {
                            $data['producto_id'] = isset($data['producto_id']) ? (int) $data['producto_id'] : null;
                            $data['cantidad'] = isset($data['cantidad']) ? (float) $data['cantidad'] : 0;
                            $data['precio_unitario'] = isset($data['precio_unitario']) ? (float) $data['precio_unitario'] : 0;
                            $data['subtotal'] = $data['cantidad'] * $data['precio_unitario'];
                            if (isset($data['_remove_temp'])) unset($data['_remove_temp']);
                            return $data;
                        })
                        ->table([
                            TableColumn::make('Código')->width('50px'),
                            TableColumn::make('Producto')->markAsRequired()->width('200px'),
                            TableColumn::make('Cantidad')->markAsRequired()->width('100px'),
                            TableColumn::make('Precio Unitario')->markAsRequired()->width('100px'),
                            TableColumn::make('Subtotal')->markAsRequired()->width('100px'),
                            TableColumn::make('Acciones')->width('10px'),
                        ])
                        ->schema([
                            //campo para mostrar el código del producto seleccionado (no se guarda en BD)
                            TextInput::make('codigo_producto')
                                ->label('Código')
                                ->disabled()
                                ->dehydrated(false) // evitar que se persista
                                ->default(fn($get) => optional(Producto::find($get('producto_id')))->codigo_producto)
                                ->columnSpan(1),

                            Select::make('producto_id')
                                ->label('Producto')
                                ->relationship('producto', 'nombre_producto')
                                ->searchable()
                                ->required()
                                ->preload()
                                ->reactive()
                                ->afterStateHydrated(function ($state, $set) {
                                    // al hidratar fila (editar existente) rellenar código
                                    $set('codigo_producto', $state ? optional(Producto::find($state))->codigo_producto : null);
                                })
                                ->afterStateUpdated(function ($state, $set, $get) {
                                    // recalcular precios/subtotales
                                    self::recalcularFila($set, $get, $get('../../tipo_precio'));

                                    // rellenar campo código con el valor del producto seleccionado
                                    $codigo = null;
                                    if ($state) {
                                        $p = Producto::find($state);
                                        $codigo = $p?->codigo_producto ?? null;
                                    }
                                    $set('codigo_producto', $codigo);
                                })
                                ->columnSpan(2),

                            TextInput::make('cantidad')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn($state, $set, $get) => self::recalcularFila($set, $get, $get('../../tipo_precio')))
                                ->columnSpan(1),

                            TextInput::make('precio_unitario')
                                ->prefix('$')
                                ->currencyMask(".", ",", 0)
                                ->numeric()
                                ->default(0)
                                ->required()
                                ->live(onBlur: true)
                                // ahora editable por el usuario; si el usuario cambia este valor
                                // recalculamos subtotal sin sobreescribir el precio
                                ->readOnly(false)
                                ->afterStateUpdated(function ($state, $set, $get) {
                                    // recalcula solo con el precio unitario proporcionado por el usuario
                                    self::recalcularDesdePrecioManual($set, $get);
                                })
                                ->columnSpan(1),

                            TextInput::make('subtotal')
                                ->prefix('$')
                                ->currencyMask(".", ",", 0)
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->columnSpan(1),
                        ])
                        ->addActionLabel('Añadir Producto')
                        ->deleteAction(fn(\Filament\Actions\Action $action) => $action->after(function ($record, $set, $get) {
                            self::recalcularTodo($set, $get, $get('tipo_precio'));
                        })),
                ]),
        ];
    }

    // sección abonos (visible solo facturado)
    protected static function sectionAbonos(): array
    {
        return [
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
                            Section::make('Datos del abono')->schema([
                                DateTimePicker::make('fecha')->label('Fecha')->required()->default(now())->columnSpan(1),
                                TextInput::make('monto')->label('Monto')->prefix('$')->inputMode('decimal')->currencyMask(".", ",", 0)->required()->stripCharacters('.')->live(onBlur: true)->numeric()->columnSpan(1),
                                Select::make('forma_pago')->label('Forma de pago')->options([
                                    'EFECTIVO' => 'Efectivo',
                                    'TARJETA' => 'Tarjeta',
                                    'NEQUI' => 'Nequi',
                                    'DAVIPLATA' => 'Daviplata',
                                    'PSE' => 'PSE',
                                    'TRANSFERENCIA' => 'Transferencia',
                                    'OTRO' => 'Otro',
                                ])->required()->columnSpan(1),
                                Textarea::make('descripcion')->label('Descripción')->default(null)->columnSpan(2),
                                Select::make('user_id')->label('Usuario que registra')->relationship('user', 'name')->searchable()->preload()->required()->columnSpan(1),
                            ])->columns(3)->columnSpan(2),

                            Section::make('Soporte')->schema([
                                FileUpload::make('imagen')->label('Comprobante o evidencia')->directory('abonos')->image()->imagePreviewHeight('200')->columnSpanFull(),
                            ])->columnSpan(1),
                        ])
                        ->addActionLabel('Añadir Abono')
                        ->columns(3)
                        ->columnSpan(4)
                        ->disabled(fn($get) => $get('estado') === 'ANULADO')
                        ->hidden(fn($get) => $get('estado') === 'ANULADO'),
                ])
                ->afterStateUpdated(function ($set, $get) {
                    self::recalcularAbonos($set, $get);
                }),
        ];
    }

    // ---------- helpers: recalcular todo / fila / abonos ----------
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
        self::recalcularAbonos($set, $get);
    }

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
        $detalles = $get('../../detalles') ?? [];
        $totalPedido = collect($detalles)->sum(fn($d) => $d['subtotal'] ?? 0);
        $set('../../subtotal', $totalPedido);
        self::recalcularAbonos($set, $get);
        self::buscarCodigoProducto($productoId);
    }

    private static function recalcularAbonos(callable $set, callable $get): void
    {
        $paths = ['', '../../', '../../../'];
        $basePath = null;
        foreach ($paths as $p) {
            $maybe = $get($p . 'abonos');
            if (!is_null($maybe)) {
                $basePath = $p;
                break;
            }
        }
        if ($basePath === null) $basePath = '';
        $abonos = $get($basePath . 'abonos') ?? [];
        $totalAbonos = collect($abonos)->sum(fn($abono) => (float) ($abono['monto'] ?? 0));
        $currentAbono = (float) ($get($basePath . 'abono') ?? 0);
        if (round($currentAbono, 4) !== round($totalAbonos, 4)) $set($basePath . 'abono', $totalAbonos);
        $subtotal = (float) ($get($basePath . 'subtotal') ?? 0);
        $descuento = (float) ($get($basePath . 'descuento') ?? 0);
        $totalAPagar = $subtotal - $totalAbonos - $descuento;
        $totalAPagar = $totalAPagar < 0 ? 0 : $totalAPagar;
        $currentTotal = (float) ($get($basePath . 'total_a_pagar') ?? 0);
        if (round($currentTotal, 4) !== round($totalAPagar, 4)) $set($basePath . 'total_a_pagar', $totalAPagar);
    }

    private static function buscarCodigoProducto(int $productoId): string
    {
        $producto = Producto::find($productoId);
        return $producto ? $producto->codigo_producto : '-';
    }

    private static function recalcularDesdePrecioManual(callable $set, callable $get): void
    {
        $cantidad = (float) ($get('cantidad') ?? 0);
        $precio = (float) ($get('precio_unitario') ?? 0);

        $subtotal = $cantidad * $precio;
        $set('subtotal', $subtotal);

        // recalcular subtotal general del pedido (busca en el contexto del repeater)
        $detalles = $get('../../detalles') ?? [];
        $totalPedido = collect($detalles)->sum(fn($d) => (float) ($d['subtotal'] ?? 0));
        $set('../../subtotal', $totalPedido);

        // recalcular abonos/total a pagar
        self::recalcularAbonos($set, $get);
    }
}
