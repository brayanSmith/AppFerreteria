<?php

namespace App\Filament\Resources\ComprasFacturadas\Pages;

use App\Filament\Resources\ComprasFacturadas\ComprasFacturadasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComprasFacturadas extends ListRecords
{
    protected static string $resource = ComprasFacturadasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
