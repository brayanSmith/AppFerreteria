<?php

namespace App\Filament\Resources\PedidosEstadoPagoEnCarteras\Pages;

use App\Filament\Resources\PedidosEstadoPagoEnCarteras\PedidosEstadoPagoEnCarteraResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPedidosEstadoPagoEnCartera extends EditRecord
{
    protected static string $resource = PedidosEstadoPagoEnCarteraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //DeleteAction::make(),
        ];
    }
}
