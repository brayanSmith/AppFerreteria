<?php

namespace App\Filament\Resources\ComprasEstadoEnCarteras\Tables;

use App\Filament\Resources\Compras\Tables\Concerns\HasCompraTable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class ComprasEstadoEnCarterasTable
{
    use HasCompraTable;

    public static function configure(Table $table): Table
    {
        return self::configureComprasTable($table)
         ->modifyQueryUsing(fn ($query) => $query->where('estado_pago', 'EN_CARTERA')->where('estado', 'FACTURADO'));
    }
}
