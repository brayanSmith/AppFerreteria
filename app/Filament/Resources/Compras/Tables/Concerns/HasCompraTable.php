<?php
namespace App\Filament\Resources\Compras\Tables\Concerns;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

trait HasCompraTable
{
    /**
     * Configura la tabla común de Compras para reutilizar en varios Resources.
     */
    public static function configureComprasTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fecha')
                    ->label('Fecha Recibido')
                    ->date()
                    ->sortable(),
                TextColumn::make('factura')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('proveedor.nombre_proveedor')
                    ->label('Proveedor')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tipo_compra')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('metodo_pago')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('estado')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->hidden(true),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
