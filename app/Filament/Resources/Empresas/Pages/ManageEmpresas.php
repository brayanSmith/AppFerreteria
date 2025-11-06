<?php

namespace App\Filament\Resources\Empresas\Pages;

use App\Filament\Resources\Empresas\EmpresaResource;
use App\Models\Empresa;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEmpresas extends ManageRecords
{
    protected static string $resource = EmpresaResource::class;

    protected function getHeaderActions(): array
    {
        // Solo mostrar el botón de crear si no hay registros
        if (Empresa::count() > 0) {
            return [];
        }

        return [
            CreateAction::make(),
        ];
    }
}
