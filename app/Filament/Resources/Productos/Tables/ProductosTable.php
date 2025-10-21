<?php

namespace App\Filament\Resources\Productos\Tables;

use Filament\Actions\Action;
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
                /*TextColumn::make('descripcion_producto')
                    ->searchable(),*/
                /*TextColumn::make('Categoria.nombre_categoria')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('SubCategoria.nombre_sub_categoria')
                    ->sortable()
                    ->searchable(),*/
                TextColumn::make('costo_producto')
                    ->label('Costo')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('valor_detal_producto')
                    ->label('Detal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('valor_mayorista_producto')
                    ->label('Mayorista')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('valor_ferretero_producto')
                    ->label('Ferretero')
                    ->numeric()
                    ->sortable(),
                /*TextColumn::make('tipo_producto')
                    ->sortable()
                    ->searchable(),*/

                TextColumn::make('Bodega.nombre_bodega')
                    ->label('Bodega')
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

                //crearemos una columna virtual que muestre cuanto se ha trasladado en la bodega con el id = 1
                TextColumn::make('trasladado_bodega_1')
                    ->label('Stock Bodega 1')
                    ->getStateUsing(function ($record) {
                        $trasladado = \App\Models\Traslado::where('producto_id', $record->id)
                            ->where('bodega_id', 1)
                            ->sum('cantidad');
                        $ventas = \App\Models\DetallePedido::where('producto_id', $record->id)
                            ->whereHas('pedido', function ($query) {
                                $query->where('bodega_id', 1)
                                    ->where('estado', 'FACTURADO');
                            })
                            ->sum('cantidad');
                        $stockBodega1= $trasladado -= $ventas;
                        return $stockBodega1;
                    }),

                    TextColumn::make('trasladado_bodega_2')
                    ->label('Stock Bodega 2')
                    ->getStateUsing(function ($record) {
                        $trasladado = \App\Models\Traslado::where('producto_id', $record->id)
                            ->where('bodega_id', 2)
                            ->sum('cantidad');
                        $ventas = \App\Models\DetallePedido::where('producto_id', $record->id)
                            ->whereHas('pedido', function ($query) {
                                $query->where('bodega_id', 2)
                                    ->where('estado', 'FACTURADO');
                            })
                            ->sum('cantidad');
                        $stockBodega2= $trasladado -= $ventas;
                        return $stockBodega2;
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                //ViewAction::make(),
                EditAction::make(),

                //Vamos a crear una accion que me abra un modal correspondiente al modelo Traslado
                Action::make('crearTraslado')
                    ->label('Crear Traslado')
                    ->modalHeading('Crear Traslado')
                    ->modalWidth('md')
                    ->form([
                        // Campo para seleccionar la bodega
                        \Filament\Forms\Components\Select::make('bodega_id')
                            ->label('Bodega')
                            ->relationship('Bodega', 'nombre_bodega')
                            ->required(),

                        // Campo para ingresar la cantidad a trasladar
                        \Filament\Forms\Components\TextInput::make('cantidad')
                            ->label('Cantidad')
                            ->numeric()
                            ->minValue(1)
                            ->required(),

                        // Campo para observaciones
                        \Filament\Forms\Components\Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->rows(2),
                    ])
                    ->action(function ($record, $data) {
                        // Crear el traslado usando el modelo Traslado
                        \App\Models\Traslado::create([
                            'producto_id'      => $record->id,
                            'bodega_id'       => $data['bodega_id'],
                            'cantidad'         => $data['cantidad'],
                            'observaciones'    => $data['observaciones'] ?? null,
                        ]);
                        // Opcional: puedes actualizar stock, mostrar notificación, etc.
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //DeleteBulkAction::make(),
                ]),
            ]);
    }
}
