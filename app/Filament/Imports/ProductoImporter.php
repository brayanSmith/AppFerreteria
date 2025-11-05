<?php

namespace App\Filament\Imports;

use App\Models\Producto;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class ProductoImporter extends Importer
{
    protected static ?string $model = Producto::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('codigo_producto')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nombre_producto')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('descripcion_producto')
                ->rules(['max:255']),
            ImportColumn::make('costo_producto')
                ->requiredMapping()
                ->numeric()
                ->rules(['required']),
            ImportColumn::make('valor_detal_producto')
                ->requiredMapping()
                ->numeric()
                ->rules(['required']),
            ImportColumn::make('valor_mayorista_producto')
                ->requiredMapping()
                ->numeric()
                ->rules(['required']),
            ImportColumn::make('valor_ferretero_producto')
                ->requiredMapping()
                ->numeric()
                ->rules(['required']),
            ImportColumn::make('imagen_producto')
                ->rules(['max:255']),
            ImportColumn::make('bodega_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('categoria_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('sub_categoria_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('stock')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('entradas')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('salidas')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('activo')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
            ImportColumn::make('tipo_producto')
                ->rules(['max:255']),
            ImportColumn::make('peso_producto')
                ->numeric(),
                //->rules(['double']),
            ImportColumn::make('ubicacion_producto')
                ->rules(['max:255']),
            ImportColumn::make('alerta_producto')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('empaquetado_externo')
                ->rules(['max:255']),
            ImportColumn::make('empaquetado_interno')
                ->rules(['max:255']),
            ImportColumn::make('referencia_producto')
                ->rules(['max:255']),
            ImportColumn::make('codigo_cliente')
                ->rules(['max:255']),
            ImportColumn::make('volumen_producto'),
            ImportColumn::make('iva_producto')
                ->requiredMapping()
                ->numeric()
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): Producto
    {
        return Producto::firstOrNew([
            'codigo_producto' => $this->data['codigo_producto'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your producto import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
