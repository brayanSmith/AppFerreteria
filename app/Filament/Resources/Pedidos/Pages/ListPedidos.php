<?php

namespace App\Filament\Resources\Pedidos\Pages;

use App\Filament\Resources\Pedidos\PedidoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class ListPedidos extends ListRecords
{
    protected static string $resource = PedidoResource::class;

    public function getTabs(): array
    {
        return [
            'ALL' => Tab::make(),
            'FERRETERO' => Tab::make()
                ->modifyQueryUsing(fn (EloquentBuilder $query) => $query->where('tipo_precio', 'FERRETERO')),
            'MAYORISTA' => Tab::make()
                ->modifyQueryUsing(fn (EloquentBuilder $query) => $query->where('tipo_precio', 'MAYORISTA')),
            'DETAL' => Tab::make()
                ->modifyQueryUsing(fn (EloquentBuilder $query) => $query->where('tipo_precio', 'DETAL')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
