<?php

namespace App\Filament\Resources\Proveedors\Pages;

use App\Filament\Resources\Proveedors\ProveedorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Imports\ProveedorImporter;
use Filament\Actions\ImportAction;
use Filament\Actions\ExportAction;
use App\Filament\Exports\ProveedorExporter;

class ManageProveedors extends ManageRecords
{
    protected static string $resource = ProveedorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(ProveedorImporter::class),
            ExportAction::make()
                ->exporter(ProveedorExporter::class),
        ];
    }
}
