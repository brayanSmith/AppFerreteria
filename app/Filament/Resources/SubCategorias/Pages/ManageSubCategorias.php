<?php

namespace App\Filament\Resources\SubCategorias\Pages;

use App\Filament\Resources\SubCategorias\SubCategoriaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Imports\SubCategoriaImporter;
use Filament\Actions\ImportAction;
use Filament\Actions\ExportAction;
use App\Filament\Exports\SubCategoriaExporter;

class ManageSubCategorias extends ManageRecords
{
    protected static string $resource = SubCategoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(SubCategoriaImporter::class),
            ExportAction::make()
                ->exporter(SubCategoriaExporter::class),
        ];
    }
}
