<?php

namespace App\Filament\Resources\ComprasEstadoPagados\Pages;

use App\Filament\Resources\ComprasEstadoPagados\ComprasEstadoPagadoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComprasEstadoPagado extends EditRecord
{
    protected static string $resource = ComprasEstadoPagadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
