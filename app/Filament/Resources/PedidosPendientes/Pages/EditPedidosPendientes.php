<?php

namespace App\Filament\Resources\PedidosPendientes\Pages;

use App\Filament\Resources\PedidosPendientes\PedidosPendientesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPedidosPendientes extends EditRecord
{
    protected static string $resource = PedidosPendientesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
