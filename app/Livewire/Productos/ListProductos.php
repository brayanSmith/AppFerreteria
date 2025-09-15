<?php

namespace App\Livewire\Productos;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use App\Models\Producto;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;

class ListProductos extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => Producto::query())
            ->columns([
                //
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
                    ->money('COP')
                    ->sortable(),
                TextColumn::make('valor_detal_producto')
                    ->numeric()
                    ->money('COP')
                    ->sortable(),
                TextColumn::make('valor_mayorista_producto')
                    ->numeric()
                    ->money('COP')
                    ->sortable(),
                TextColumn::make('valor_ferretero_producto')
                    ->numeric()
                    ->money('COP')
                    ->sortable(),

                TextColumn::make('bodega_id')
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
            ->headerActions([
                //
                CreateAction::make()
                    ->url(fn(): string => route('producto.create')),
            ])
            ->recordActions([
                //
                ViewAction::make(),

                /*EditAction::make()
                    ->successRedirectUrl(fn(Producto $record): string => route('producto.edit', [
                        'productos' => $record,
                    ])),*/

                EditAction::make()
                    ->url(fn(Producto $record): string => route('producto.edit', $record)),

                DeleteAction::make(),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.productos.list-productos');
    }
}
