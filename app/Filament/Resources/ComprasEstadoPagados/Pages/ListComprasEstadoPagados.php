<?php

namespace App\Filament\Resources\ComprasEstadoPagados\Pages;

use App\Filament\Resources\ComprasEstadoPagados\ComprasEstadoPagadoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComprasEstadoPagados extends ListRecords
{
    protected static string $resource = ComprasEstadoPagadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
