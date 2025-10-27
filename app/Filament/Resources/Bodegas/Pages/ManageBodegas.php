<?php

namespace App\Filament\Resources\Bodegas\Pages;

use App\Filament\Resources\Bodegas\BodegaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Imports\BodegaImporter;
use Filament\Actions\ImportAction;
use Filament\Actions\ExportAction;
use App\Filament\Exports\BodegaExporter;

class ManageBodegas extends ManageRecords
{
    protected static string $resource = BodegaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            //
            importAction::make()
                ->importer(BodegaImporter::class),
            ExportAction::make()
                ->exporter(BodegaExporter::class),
        ];
    }
}
