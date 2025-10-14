<?php

namespace App\Filament\Resources\PedidosAnulados\Pages;

use App\Filament\Resources\PedidosAnulados\PedidosAnuladosResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPedidosAnulados extends EditRecord
{
    protected static string $resource = PedidosAnuladosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
