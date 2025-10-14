<?php

namespace App\Filament\Resources\PedidosFacturados\Pages;

use App\Filament\Resources\PedidosFacturados\PedidosFacturadosResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditPedidosFacturados extends EditRecord
{
    protected static string $resource = PedidosFacturadosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            //este solo va a aparece cuando el estado sea igual a PENDIENTE
            Action::make('download_pdf')
                ->label(fn () => 'Descargar PDF (' . ($this->record->contador_impresiones ?? 0) . ')')
                //->icon('heroicon-o-document-download')
                ->url(fn () => route('pedidos.pdf.download', $this->record->id))
                ->openUrlInNewTab(),
            //este solo va a aparece cuando el estado sea igual a FACTURADO
            Action::make('download_pdf_facturado')
                ->label(fn () => 'Descargar PDF Facturado')
                //->icon('heroicon-o-document-download')
                ->url(fn () => route('pedidosFacturados.pdf.download', $this->record->id))
                ->openUrlInNewTab(),
        ];
    }
}
