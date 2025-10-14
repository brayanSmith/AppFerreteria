<?php

namespace App\Filament\Resources\PedidosFacturados\Pages;

use App\Filament\Resources\PedidosFacturados\PedidosFacturadosResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPedidosFacturados extends EditRecord
{
    protected static string $resource = PedidosFacturadosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
