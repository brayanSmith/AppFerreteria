<?php

namespace App\Filament\Resources\PedidosFacturados\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class PedidosFacturadosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->where('estado', 'FACTURADO'))
            ->groups([
                Group::make('fecha')
                    ->date()
                    ->collapsible(),
                Group::make('cliente.ruta.ruta')
                    ->collapsible(),

            ])->defaultGroup('fecha')
            ->columns([
                //
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
                    ->numeric(0)
                    ->sortable(),

                TextColumn::make('abono')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('descuento')
                    ->numeric(0)
                    ->sortable(),
                TextColumn::make('total_a_pagar')
                    ->label('Total a Pagar')
                    ->numeric(0)
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
                //
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
