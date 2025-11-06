<?php

namespace App\Filament\Resources\ComprasFacturadas\Pages;
 
use App\Filament\Resources\ComprasFacturadas\ComprasFacturadasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

use App\Filament\Exports\CompraExporter;
use Filament\Actions\ExportAction;

class ListComprasFacturadas extends ListRecords
{
    protected static string $resource = ComprasFacturadasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make()
            ->exporter(CompraExporter::class),

            
        ];
    }
}
