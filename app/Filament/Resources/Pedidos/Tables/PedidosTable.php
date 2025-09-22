<?php

namespace App\Filament\Resources\Pedidos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PedidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fecha')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('codigo')
                    ->searchable(),
                TextColumn::make('cliente.razon_social')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cliente.ruta.ruta')
                ->label('Ruta')
                    ->sortable(),

                TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ciudad')
                    ->searchable(),
                TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PENDIENTE' => 'warning',
                        'FACTURADO' => 'success',
                        'ANULADO' => 'danger',
                        default => 'primary',
                    }),
                TextColumn::make('metodo_pago')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'EFECTIVO' => 'success',
                        'A CREDITO' => 'info',
                        default => 'secondary',
                    }),
                TextColumn::make('tipo_precio')
                    ->badge(),
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
