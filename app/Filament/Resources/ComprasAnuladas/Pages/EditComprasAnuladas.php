<?php

namespace App\Filament\Resources\ComprasAnuladas\Pages;

use App\Filament\Resources\ComprasAnuladas\ComprasAnuladasResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComprasAnuladas extends EditRecord
{
    protected static string $resource = ComprasAnuladasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
