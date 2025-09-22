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
            'PENDIENTE' => Tab::make()
                ->modifyQueryUsing(fn (EloquentBuilder $query) => $query->where('estado', 'PENDIENTE')),
            'FACTURADO' => Tab::make()
                ->modifyQueryUsing(fn (EloquentBuilder $query) => $query->where('estado', 'FACTURADO')),
            'ANULADO' => Tab::make()
                ->modifyQueryUsing(fn (EloquentBuilder $query) => $query->where('estado', 'ANULADO')),
        ];
    }



    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
