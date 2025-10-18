<?php

namespace App\Filament\Resources\ComprasEstadoEnCarteras\Pages;

use App\Filament\Resources\ComprasEstadoEnCarteras\ComprasEstadoEnCarteraResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComprasEstadoEnCartera extends EditRecord
{
    protected static string $resource = ComprasEstadoEnCarteraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
