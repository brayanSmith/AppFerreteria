<?php

namespace App\Filament\Resources\Productos\Pages;

use App\Filament\Resources\Productos\ProductoResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Widgets\SegmentadorProductosWidget;
use App\Filament\Widgets\ProductosActivosWidget;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;

class ListProductos extends ListRecords
{
    protected static string $resource = ProductoResource::class;

    protected function getTableActions(): array
    {
        return [


            Action::make('crearTraslado')
                ->label('Crear Traslado')
                ->color('success'),
        ];
    }

     protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
