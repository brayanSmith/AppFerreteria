<?php

namespace App\Filament\Resources\PedidosEstadoPagoSaldados\Pages;

use App\Filament\Resources\PedidosEstadoPagoSaldados\PedidosEstadoPagoSaldadoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPedidosEstadoPagoSaldados extends ListRecords
{
    protected static string $resource = PedidosEstadoPagoSaldadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }
}
