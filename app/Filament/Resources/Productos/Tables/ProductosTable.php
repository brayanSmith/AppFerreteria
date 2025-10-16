<?php

namespace App\Filament\Resources\Productos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('imagen_producto')
                    ->label('Imagen')
                    ->disk('public')
                    ->size(50)
                    ->circular(),
                TextColumn::make('codigo_producto')
                    ->label('Código')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nombre_producto')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('descripcion_producto')
                    ->searchable(),
                TextColumn::make('Categoria.nombre_categoria')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('SubCategoria.nombre_sub_categoria')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('costo_producto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('valor_detal_producto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('valor_mayorista_producto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('valor_ferretero_producto')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('bodega_id')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),

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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
