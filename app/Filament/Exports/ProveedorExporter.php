<?php

namespace App\Filament\Exports;

use App\Models\Proveedor;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class ProveedorExporter extends Exporter
{
    protected static ?string $model = Proveedor::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('nombre_proveedor'),
            ExportColumn::make('razon_social_proveedor'),
            ExportColumn::make('nit_proveedor'),
            ExportColumn::make('rut_proveedor_imagen'),
            ExportColumn::make('tipo_proveedor'),
            ExportColumn::make('categoria_proveedor'),
            ExportColumn::make('departamento_proveedor'),
            ExportColumn::make('ciudad_proveedor'),
            ExportColumn::make('direccion_proveedor'),
            ExportColumn::make('telefono_proveedor'),
            ExportColumn::make('banco_proveedor'),
            ExportColumn::make('tipo_cuenta_proveedor'),
            ExportColumn::make('numero_cuenta_proveedor'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your proveedor export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
