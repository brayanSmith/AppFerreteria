<?php

namespace App\Filament\Resources\Catalogos\Pages;

use App\Filament\Resources\Catalogos\CatalogoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCatalogos extends ManageRecords
{
    protected static string $resource = CatalogoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }
}
