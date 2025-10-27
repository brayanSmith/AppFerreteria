<?php

namespace App\Filament\Imports;

use App\Models\Bodega;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class BodegaImporter extends Importer
{
    protected static ?string $model = Bodega::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nombre_bodega')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('ubicacion_bodega')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): Bodega
    {
        return Bodega::firstOrNew([
            'nombre_bodega' => $this->data['nombre_bodega'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your bodega import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
