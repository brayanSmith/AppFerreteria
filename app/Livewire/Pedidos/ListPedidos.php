<?php

namespace App\Livewire\Pedidos;

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
use App\Models\Pedido;
use Dom\Text;
use Filament\Tables\Columns\TextColumn;

class ListPedidos extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Pedido::query())
            ->columns([
                //
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cliente_id')
                    ->label('Cliente')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('estado')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('metodo_pago')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tipo_precio')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('subtotal')
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.pedidos.list-pedidos');
    }
}
