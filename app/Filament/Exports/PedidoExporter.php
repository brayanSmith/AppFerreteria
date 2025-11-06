<?php

namespace App\Filament\Exports;

use App\Models\Pedido;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class PedidoExporter extends Exporter
{
    protected static ?string $model = Pedido::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('codigo'),
            ExportColumn::make('cliente_id'),
            ExportColumn::make('fecha'),
            ExportColumn::make('dias_plazo_vencimiento'),
            ExportColumn::make('fecha_vencimiento'),
            ExportColumn::make('ciudad'),
            ExportColumn::make('estado'),
            ExportColumn::make('stock_retirado'),
            ExportColumn::make('en_cartera'),
            ExportColumn::make('metodo_pago'),
            ExportColumn::make('tipo_precio'),
            ExportColumn::make('tipo_venta'),
            ExportColumn::make('estado_pago'),
            ExportColumn::make('bodega_id'),
            ExportColumn::make('primer_comentario'),
            ExportColumn::make('segundo_comentario'),
            ExportColumn::make('subtotal'),
            ExportColumn::make('abono'),
            ExportColumn::make('descuento'),
            ExportColumn::make('flete'),
            ExportColumn::make('iva'),
            ExportColumn::make('total_a_pagar'),
            ExportColumn::make('contador_impresiones'),
            ExportColumn::make('impresa'),
            ExportColumn::make('user_id'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your pedido export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
