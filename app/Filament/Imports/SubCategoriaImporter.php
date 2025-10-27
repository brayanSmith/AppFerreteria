<?php

namespace App\Filament\Imports;

use App\Models\SubCategoria;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class SubCategoriaImporter extends Importer
{
    protected static ?string $model = SubCategoria::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nombre_sub_categoria')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('categoria_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): SubCategoria
    {
        return SubCategoria::firstOrNew([
            'nombre_sub_categoria' => $this->data['nombre_sub_categoria'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your sub categoria import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
