<?php

namespace App\Filament\Resources\Compras\Schemas\Concerns;

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


trait HasCompraSections
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
                        // posibles nombres de campo fecha en distintos formularios
                        $dateKeys = ['fecha', 'fecha_abono', 'fecha_abono_compra', 'fecha_abono_pedido'];
                        $dates = collect($abonos)
                            ->map(function ($a) use ($dateKeys) {
                                foreach ($dateKeys as $k) {
                                    if (! empty($a[$k])) {
                                        try { return Carbon::parse($a[$k]); } catch (\Throwable $e) { return null; }
                                    }
                                }
                                return null;
                            })
                            ->filter()
                            ->sort();
                        $last = $dates->last();
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
                        $dateKeys = ['fecha', 'fecha_abono', 'fecha_abono_compra', 'fecha_abono_pedido'];
                        $dates = collect($abonos)
                            ->map(function ($a) use ($dateKeys) {
                                foreach ($dateKeys as $k) {
                                    if (! empty($a[$k])) {
                                        try { return Carbon::parse($a[$k]); } catch (\Throwable $e) { return null; }
                                    }
                                }
                                return null;
                            })
                            ->filter()
                            ->sort();
                        $last = $dates->last();
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
            Section::make('Datos de la compra')
                ->columns(4)
                ->columnSpan(1)
                ->schema([
                    TextInput::make('factura')->columnSpan(1)->label('Factura')->required()->unique(),

                    Select::make('proveedor_id')
                        ->label('Proveedor')
                        ->relationship('proveedor', 'razon_social_proveedor')
                        ->searchable()
                        ->required()
                        ->preload()
                        ->reactive()
                        ->columnSpan(3),

                    DatePicker::make('fecha')
                        ->label('Fecha de Recibido')
                        ->required()
                        ->columnSpan(2)
                        ->reactive()
                        ->afterStateUpdated(function ($state, $set, $get) {
                            if ($state) {
                                try {
                                    $dias = (int) ($get('dias_plazo_vencimiento') ?? 0);
                                    $fechaCarbon = Carbon::parse($state);
                                    $nuevaFechaVenc = $fechaCarbon->copy()->addDays($dias);
                                    $set('fecha_vencimiento', $nuevaFechaVenc->toDateString());
                                } catch (\Throwable $e) {
                                    // no hacer nada si hay error de parseo
                                }
                            } else {
                                $set('fecha_vencimiento', null);
                            }
                        }),

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

                    ToggleButtons::make('estado')->options([
                        'PENDIENTE' => 'Pendiente',
                        'FACTURADO' => 'Facturado',
                        'ANULADO'   => 'Anulado',
                    ])->default('PENDIENTE')->required()->columnSpan(2)->grouped(),

                    Select::make('tipo_compra')->options([
                        'REMISIONADA' => 'Remisionada',
                        'ELECTRONICA' => 'Electrónica',
                    ])->required()->columnSpan(2),

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
            Section::make('Observaciones')
                ->columns(1)
                ->schema([
                    Textarea::make('observaciones')->label('Observaciones')->rows(2)->columnSpanFull(),
                ])->columnSpanFull()->collapsed(true)->collapsible(),
        ];
    }

    // sección detalles
    protected static function sectionDetalles(): array
    {
        return [
            Section::make('Detalles de la compra')
                ->columnSpanFull()
                ->schema([
                    Repeater::make('detallesCompra')
                        ->relationship('detallesCompra')
                        ->label(function ($get) {
                            $detalles = $get('detallesCompra') ?? [];
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
                                ->searchable()
                                ->required()
                                ->preload()
                                // Opciones con "codigo - nombre"
                                ->options(fn() => Producto::orderBy('nombre_producto')
                                    ->get()
                                    ->mapWithKeys(fn($p) => [$p->id => ($p->codigo_producto ? $p->codigo_producto . ' - ' : '') . $p->nombre_producto])
                                    ->toArray()
                                )
                                ->reactive()
                                ->afterStateHydrated(function ($state, $set) {
                                    // al hidratar fila (editar existente) rellenar código
                                    $set('codigo_producto', $state ? optional(Producto::find($state))->codigo_producto : null);
                                })
                                ->afterStateUpdated(function ($state, $set, $get) {
                                    // recalcular precios/subtotales
                                    self::recalcularFila($set, $get);

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
                                ->afterStateUpdated(fn($state, $set, $get) => self::recalcularFila($set, $get))
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
                            self::recalcularTodo($set, $get);
                        })),
                ]),
        ];
    }

    // sección abonos (visible solo facturado)
    protected static function sectionAbonos(): array
    {
        return [
            Section::make('Abonos Compra')
                ->columnSpanFull()
                ->visible(fn($get) => $get('estado') === 'FACTURADO')
                ->schema([
                    Repeater::make('abonos')
                        ->relationship('abonoCompra')
                        ->label(function ($get) {
                            $abonos = $get('abonos') ?? [];
                            $total = collect($abonos)->sum(fn($abono) => (float) ($abono['monto_abono_compra'] ?? 0));
                            return 'Abonos realizados (Total: $' . number_format($total, 0, ',', '.') . ')';
                        })
                        ->schema([
                            Section::make('Datos del abono')->schema([
                                DateTimePicker::make('fecha_abono_compra')->label('Fecha')->required()->default(now())->columnSpan(1),
                                TextInput::make('monto_abono_compra')->label('Monto')->prefix('$')->inputMode('decimal')->currencyMask(".", ",", 0)->required()->stripCharacters('.')->live(onBlur: true)->numeric()->columnSpan(1),
                                Select::make('forma_pago_abono_compra')->label('Forma de pago')->options([
                                    'EFECTIVO' => 'Efectivo',
                                    'TARJETA' => 'Tarjeta',
                                    'NEQUI' => 'Nequi',
                                    'DAVIPLATA' => 'Daviplata',
                                    'PSE' => 'PSE',
                                    'TRANSFERENCIA' => 'Transferencia',
                                    'OTRO' => 'Otro',
                                ])->required()->columnSpan(1),
                                Textarea::make('descripcion_abono_compra')->label('Descripción')->default(null)->columnSpan(2),
                                Select::make('user_id')->label('Usuario que registra')->relationship('user', 'name')->searchable()->preload()->required()->columnSpan(1),
                            ])->columns(3)->columnSpan(2),

                            Section::make('Soporte')->schema([
                                FileUpload::make('imagen_abono_compra')->label('Comprobante o evidencia')->directory('abonos')->image()->imagePreviewHeight('200')->columnSpanFull(),
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
    private static function recalcularTodo(callable $set, callable $get): void
    {
        // detectar el nombre real del repeater usado en este form
        $repeaterKeys = ['detalles', 'detallesCompra', 'detalles_compra'];
        $repeaterKey = null;
        foreach ($repeaterKeys as $k) {
            try {
                $maybe = $get($k);
            } catch (\Throwable $e) {
                $maybe = null;
            }
            if (!is_null($maybe)) {
                $repeaterKey = $k;
                break;
            }
        }

        $detalles = $repeaterKey ? ($get($repeaterKey) ?? []) : [];
        $subtotalGeneral = 0;

        foreach ($detalles as $index => $detalle) {
            if (empty($detalle['producto_id'])) continue;
            $producto = Producto::find($detalle['producto_id']);
            if (! $producto) continue;
            $precio = $detalle['precio_unitario'] ?? 0;
            $cantidad = $detalle['cantidad'] ?? 0;
            $subtotal = $cantidad * $precio;

            // escribir en la ruta correcta del repeater
            $set("{$repeaterKey}.{$index}.precio_unitario", $precio);
            $set("{$repeaterKey}.{$index}.subtotal", $subtotal);

            $subtotalGeneral += $subtotal;
        }

        // subtotal general (campo raíz)
        $set('subtotal', $subtotalGeneral);

        self::recalcularAbonos($set, $get);
    }

    private static function recalcularFila(callable $set, callable $get): void
    {
        $productoId = $get('producto_id');
        $cantidad   = $get('cantidad') ?? 0;
        $precio     = $get('precio_unitario') ?? 0;

        if ($productoId && (empty($precio) || $precio == 0)) {
            $producto = Producto::find($productoId);
            if ($producto) {
                // Usar costo_producto como valor por defecto; si no existe, caer a precio según tipo
                $precio = (float) ($producto->costo_producto ?? $producto->getPrecioPorTipo($get('../../tipo_precio') ?? 'DETAL') ?? 0);
                // sólo setear precio_unitario si no había un precio manual
                $set('precio_unitario', $precio);
            }
        }

        $subtotal = $cantidad * $precio;
        $set('subtotal', $subtotal);

        // detectar clave del repeater para recalcular subtotal general
        $detalles = $get('../../detalles') ?? $get('../../detallesCompra') ?? $get('../../detalles_compra') ?? [];
        $totalPedido = collect($detalles)->sum(fn($d) => (float) ($d['subtotal'] ?? 0));
        $set('../../subtotal', $totalPedido);

        self::recalcularAbonos($set, $get);

        // actualizar código producto si corresponde
        self::buscarCodigoProducto((int) $productoId);
    }

    private static function recalcularAbonos(callable $set, callable $get): void
    {
        // posibles nombres del repeater / lista de abonos en distintos formularios
        $abonosKeys = ['abonos', 'abonosCompra', 'abonos_compra', 'abonoCompra', 'abono_compra'];

        // posibles nombres de campo que contienen el importe del abono
        $montoKeys = ['monto', 'monto_abono_compra', 'monto_abono', 'amount', 'valor'];

        $basePath = null;
        $abonos = null;

        // encontrar la ruta donde está el array de abonos
        foreach (['', '../../', '../../../', '../../../../'] as $p) {
            foreach ($abonosKeys as $k) {
                try {
                    $maybe = $get($p . $k);
                } catch (\Throwable $e) {
                    $maybe = null;
                }
                if (! is_null($maybe)) {
                    $basePath = $p;
                    $abonos = $maybe;
                    break 2;
                }
            }
        }

        if ($abonos === null) {
            // no hay abonos en el formulario
            $basePath = $basePath ?? '';
            $abonos = [];
        }

        // sumar importes buscando cualquier nombre válido
        $totalAbonos = 0.0;
        foreach ($abonos as $abono) {
            foreach ($montoKeys as $mk) {
                if (isset($abono[$mk])) {
                    $totalAbonos += (float) $abono[$mk];
                    break;
                }
            }
        }

        // establecer el campo 'abono' en el contexto correcto (si existe)
        $currentAbono = (float) ($get($basePath . 'abono') ?? 0);
        if (round($currentAbono, 4) !== round($totalAbonos, 4)) {
            $set($basePath . 'abono', $totalAbonos);
        }

        // recalcular total a pagar (usa subtotal / descuento / abonos)
        $subtotal = (float) ($get($basePath . 'subtotal') ?? 0);
        $descuento = (float) ($get($basePath . 'descuento') ?? 0);
        $totalAPagar = $subtotal - $totalAbonos - $descuento;
        $totalAPagar = $totalAPagar < 0 ? 0 : $totalAPagar;

        $currentTotal = (float) ($get($basePath . 'total_a_pagar') ?? 0);
        if (round($currentTotal, 4) !== round($totalAPagar, 4)) {
            $set($basePath . 'total_a_pagar', $totalAPagar);
        }
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

        // detectar clave del repeater para recalcular subtotal general
        $detalles = $get('../../detalles') ?? $get('../../detallesCompra') ?? $get('../../detalles_compra') ?? [];
        $totalPedido = collect($detalles)->sum(fn($d) => (float) ($d['subtotal'] ?? 0));
        $set('../../subtotal', $totalPedido);

        self::recalcularAbonos($set, $get);
    }
}
