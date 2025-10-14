<?php

namespace App\Filament\Resources\PedidosEstadoPagoEnCarteras\Pages;

use App\Filament\Resources\PedidosEstadoPagoEnCarteras\PedidosEstadoPagoEnCarteraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPedidosEstadoPagoEnCarteras extends ListRecords
{
    protected static string $resource = PedidosEstadoPagoEnCarteraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
