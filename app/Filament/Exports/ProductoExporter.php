<?php

namespace App\Filament\Exports;

use App\Models\Producto;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class ProductoExporter extends Exporter
{
    protected static ?string $model = Producto::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('codigo_producto'),
            ExportColumn::make('nombre_producto'),
            ExportColumn::make('descripcion_producto'),
            ExportColumn::make('costo_producto'),
            ExportColumn::make('valor_detal_producto'),
            ExportColumn::make('valor_mayorista_producto'),
            ExportColumn::make('valor_ferretero_producto'),
            ExportColumn::make('imagen_producto'),
            ExportColumn::make('bodega_id'),
            ExportColumn::make('categoria_id'),
            ExportColumn::make('sub_categoria_id'),
            ExportColumn::make('stock'),
            ExportColumn::make('entradas'),
            ExportColumn::make('salidas'),
            ExportColumn::make('activo'),
            ExportColumn::make('tipo_producto'),
            ExportColumn::make('peso_producto'),
            ExportColumn::make('ubicacion_producto'),
            ExportColumn::make('alerta_producto'),
            ExportColumn::make('empaquetado_externo'),
            ExportColumn::make('empaquetado_interno'),
            ExportColumn::make('referencia_producto'),
            ExportColumn::make('codigo_cliente'),
            ExportColumn::make('volumen_producto'),
            ExportColumn::make('iva_producto'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your producto export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
