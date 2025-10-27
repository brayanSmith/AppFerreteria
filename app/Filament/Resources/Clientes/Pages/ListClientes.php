<?php

namespace App\Filament\Resources\Clientes\Pages;

use App\Filament\Resources\Clientes\ClienteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Imports\ClienteImporter;
use Filament\Actions\ImportAction;
use Filament\Actions\ExportAction;
use App\Filament\Exports\ClienteExporter;

class ListClientes extends ListRecords
{
    protected static string $resource = ClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(ClienteImporter::class),
            ExportAction::make()
                ->exporter(ClienteExporter::class),
        ];
    }
}
