<?php

namespace App\Filament\Exports;

use App\Models\Compra;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class CompraExporter extends Exporter
{
    protected static ?string $model = Compra::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('factura'),
            ExportColumn::make('proveedor_id'),
            ExportColumn::make('fecha'),
            ExportColumn::make('dias_plazo_vencimiento'),
            ExportColumn::make('fecha_vencimiento'),
            ExportColumn::make('metodo_pago'),
            ExportColumn::make('estado_pago'),
            ExportColumn::make('stock_sumado'),
            ExportColumn::make('tipo_compra'),
            ExportColumn::make('estado'),
            ExportColumn::make('observaciones'),
            ExportColumn::make('subtotal'),
            ExportColumn::make('abono'),
            ExportColumn::make('descuento'),
            ExportColumn::make('total_a_pagar'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your compra export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
