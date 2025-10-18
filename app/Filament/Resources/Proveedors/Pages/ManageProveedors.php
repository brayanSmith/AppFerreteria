<?php

namespace App\Filament\Resources\Proveedors\Pages;

use App\Filament\Resources\Proveedors\ProveedorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageProveedors extends ManageRecords
{
    protected static string $resource = ProveedorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
