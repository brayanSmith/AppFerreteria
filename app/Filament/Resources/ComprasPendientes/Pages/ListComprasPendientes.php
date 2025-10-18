<?php

namespace App\Filament\Resources\ComprasPendientes\Pages;

use App\Filament\Resources\ComprasPendientes\ComprasPendientesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComprasPendientes extends ListRecords
{
    protected static string $resource = ComprasPendientesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
