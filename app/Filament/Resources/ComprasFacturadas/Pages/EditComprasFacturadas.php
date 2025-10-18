<?php

namespace App\Filament\Resources\ComprasFacturadas\Pages;

use App\Filament\Resources\ComprasFacturadas\ComprasFacturadasResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComprasFacturadas extends EditRecord
{
    protected static string $resource = ComprasFacturadasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
