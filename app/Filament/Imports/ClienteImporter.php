<?php

namespace App\Filament\Imports;

use App\Models\Cliente;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class ClienteImporter extends Importer
{
    protected static ?string $model = Cliente::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('tipo_documento')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('numero_documento')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('razon_social')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('direccion')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('telefono')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('ciudad')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('representante_legal')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('activo')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
            ImportColumn::make('novedad')
                ->rules(['max:255']),
            ImportColumn::make('ruta_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('comercial_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('tipo_cliente')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('rut_imagen')
                ->rules(['max:255']),
            ImportColumn::make('retenedor_fuente')
                ->requiredMapping()
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): Cliente
    {
        return Cliente::firstOrNew([
            'numero_documento' => $this->data['numero_documento'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your cliente import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
