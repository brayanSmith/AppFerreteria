<?php

namespace App\Filament\Resources\PedidosEstadoPagoSaldados\Pages;

use App\Filament\Resources\PedidosEstadoPagoSaldados\PedidosEstadoPagoSaldadoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPedidosEstadoPagoSaldado extends EditRecord
{
    protected static string $resource = PedidosEstadoPagoSaldadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
