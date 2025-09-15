<?php

namespace App\Filament\Resources\Medidas\Pages;

use App\Filament\Resources\Medidas\MedidaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMedidas extends ManageRecords
{
    protected static string $resource = MedidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
