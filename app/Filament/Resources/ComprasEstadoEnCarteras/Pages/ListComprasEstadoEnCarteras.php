<?php

namespace App\Filament\Resources\ComprasEstadoEnCarteras\Pages;

use App\Filament\Resources\ComprasEstadoEnCarteras\ComprasEstadoEnCarteraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComprasEstadoEnCarteras extends ListRecords
{
    protected static string $resource = ComprasEstadoEnCarteraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
