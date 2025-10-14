<?php

namespace App\Filament\Resources\Pedidos\Pages;

use App\Filament\Resources\Pedidos\PedidoResource;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Tabs;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class ListPedidos extends ListRecords
{
    protected static string $resource = PedidoResource::class;

    public function getTabs(): array
{
    return [
        'TODOS' => Tab::make(),
            //->badge(fn () => \App\Models\Pedido::count()),

        'PENDIENTE' => Tab::make()
            ->modifyQueryUsing(fn (EloquentBuilder $query) => $query->where('estado', 'PENDIENTE')),
            //->badge(fn () => \App\Models\Pedido::where('estado', 'PENDIENTE')->count()),

        'FACTURADO' => Tab::make()
            ->modifyQueryUsing(fn (EloquentBuilder $query) => $query->where('estado', 'FACTURADO')),
           // ->badge(fn () => \App\Models\Pedido::where('estado', 'FACTURADO')->count()),

        'ANULADO' => Tab::make()
            ->modifyQueryUsing(fn (EloquentBuilder $query) => $query->where('estado', 'ANULADO')),
           // ->badge(fn () => \App\Models\Pedido::where('estado', 'ANULADO')->count()),
    ];
}




    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }
}
