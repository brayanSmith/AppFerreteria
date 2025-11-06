<?php

namespace App\Filament\Resources\PedidosFacturados\Pages;
 
use App\Filament\Resources\PedidosFacturados\PedidosFacturadosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

use App\Filament\Exports\PedidoExporter;
use Filament\Actions\ExportAction;

class ListPedidosFacturados extends ListRecords
{
    protected static string $resource = PedidosFacturadosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make()
            ->exporter(PedidoExporter::class),
        ];
    }
}
