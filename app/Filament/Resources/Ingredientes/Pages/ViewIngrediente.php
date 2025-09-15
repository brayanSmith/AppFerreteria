<?php

namespace App\Filament\Resources\Ingredientes\Pages;

use App\Filament\Resources\Ingredientes\IngredienteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewIngrediente extends ViewRecord
{
    protected static string $resource = IngredienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
