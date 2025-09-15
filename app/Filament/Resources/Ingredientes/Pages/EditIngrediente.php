<?php

namespace App\Filament\Resources\Ingredientes\Pages;

use App\Filament\Resources\Ingredientes\IngredienteResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditIngrediente extends EditRecord
{
    protected static string $resource = IngredienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
