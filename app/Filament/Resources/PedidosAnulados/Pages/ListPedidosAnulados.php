<?php

namespace App\Filament\Resources\PedidosAnulados\Pages;

use App\Filament\Resources\PedidosAnulados\PedidosAnuladosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPedidosAnulados extends ListRecords
{
    protected static string $resource = PedidosAnuladosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
