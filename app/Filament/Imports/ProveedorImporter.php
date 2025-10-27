<?php

namespace App\Filament\Imports;

use App\Models\Proveedor;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class ProveedorImporter extends Importer
{
    protected static ?string $model = Proveedor::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nombre_proveedor')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('razon_social_proveedor')
                ->rules(['max:255']),
            ImportColumn::make('nit_proveedor')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('rut_proveedor_imagen')
                ->rules(['max:255']),
            ImportColumn::make('tipo_proveedor')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('categoria_proveedor')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('departamento_proveedor')
                ->rules(['max:255']),
            ImportColumn::make('ciudad_proveedor')
                ->rules(['max:255']),
            ImportColumn::make('direccion_proveedor')
                ->rules(['max:255']),
            ImportColumn::make('telefono_proveedor')
                ->rules(['max:255']),
            ImportColumn::make('banco_proveedor')
                ->rules(['max:255']),
            ImportColumn::make('tipo_cuenta_proveedor'),
            ImportColumn::make('numero_cuenta_proveedor')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): Proveedor
    {
        return Proveedor::firstOrNew([
            'nit_proveedor' => $this->data['nit_proveedor'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your proveedor import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
