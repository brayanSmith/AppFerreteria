<?php

namespace App\Filament\Resources\ComprasPendientes\Pages;

use App\Filament\Resources\ComprasPendientes\ComprasPendientesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComprasPendientes extends EditRecord
{
    protected static string $resource = ComprasPendientesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
