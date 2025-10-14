<?php

namespace App\Filament\Resources\Pedidos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class PedidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('fecha')
                    ->date()
                    ->collapsible(),
                Group::make('cliente.ruta.ruta')
                    ->collapsible(),


            ])->defaultGroup('fecha')
            ->columns([
                TextColumn::make('fecha')
                    ->label('Fecha de Facturación')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cliente.razon_social')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cliente.ruta.ruta')
                    ->label('Ruta')
                    ->sortable(),

                TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),

                ToggleColumn::make('impresa')
                    ->label('Impresa'),

                TextColumn::make('ciudad')
                    ->searchable(),
                TextColumn::make('estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'PENDIENTE' => 'warning',
                        'FACTURADO' => 'success',
                        'ANULADO' => 'danger',
                        default => 'primary',
                    }),
                TextColumn::make('metodo_pago')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
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
                // Filtro por Ruta
                SelectFilter::make('cliente.ruta_id')
                    ->label('Ruta')
                    ->relationship('cliente.ruta', 'ruta')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                // Filtro por Cliente
                SelectFilter::make('cliente_id')
                    ->label(label: 'Cliente')
                    ->relationship('cliente', 'razon_social')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                // Filtro por Levantar Deuda (booleano)
                /*TernaryFilter::make('levantar_deuda')
                    ->label('Levantar deuda')
                    ->boolean(),*/
                SelectFilter::make('levantar_deuda')
                    ->label('Levantar deuda')
                    ->options([
                        1 => 'Si',
                        0 => 'No',
                    ]),

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
