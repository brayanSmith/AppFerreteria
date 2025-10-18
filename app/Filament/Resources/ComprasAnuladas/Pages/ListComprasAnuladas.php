<?php

namespace App\Filament\Resources\ComprasAnuladas\Pages;

use App\Filament\Resources\ComprasAnuladas\ComprasAnuladasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComprasAnuladas extends ListRecords
{
    protected static string $resource = ComprasAnuladasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
