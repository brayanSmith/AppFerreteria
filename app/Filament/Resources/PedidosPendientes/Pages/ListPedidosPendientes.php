<?php

namespace App\Filament\Resources\PedidosPendientes\Pages;

use App\Filament\Resources\PedidosPendientes\PedidosPendientesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPedidosPendientes extends ListRecords
{
    protected static string $resource = PedidosPendientesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
