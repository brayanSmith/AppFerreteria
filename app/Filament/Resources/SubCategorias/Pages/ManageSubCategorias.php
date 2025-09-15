<?php

namespace App\Filament\Resources\SubCategorias\Pages;

use App\Filament\Resources\SubCategorias\SubCategoriaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSubCategorias extends ManageRecords
{
    protected static string $resource = SubCategoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
