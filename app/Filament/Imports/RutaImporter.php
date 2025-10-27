<?php

namespace App\Filament\Imports;

use App\Models\Ruta;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class RutaImporter extends Importer
{
    protected static ?string $model = Ruta::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('ruta')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('descripcion')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): Ruta
    {
        return Ruta::firstOrNew([
            'ruta' => $this->data['ruta'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your ruta import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
