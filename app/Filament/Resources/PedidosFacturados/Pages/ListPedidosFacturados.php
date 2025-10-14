<?php

namespace App\Filament\Resources\PedidosFacturados\Pages;

use App\Filament\Resources\PedidosFacturados\PedidosFacturadosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPedidosFacturados extends ListRecords
{
    protected static string $resource = PedidosFacturadosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
