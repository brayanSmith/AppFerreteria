<?php

namespace App\Filament\Imports;

use App\Models\Categoria;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class CategoriaImporter extends Importer
{
    protected static ?string $model = Categoria::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nombre_categoria'),
                //->requiredMapping()
                //->rules(['required', 'max:255']),
        ];
    }

    public function resolveRecord(): Categoria
    {
        return new Categoria();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your categoria import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
