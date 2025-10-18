<?php

namespace App\Filament\Resources\ComprasEstadoPagados\Tables;

use App\Filament\Resources\Compras\Tables\Concerns\HasCompraTable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class ComprasEstadoPagadosTable
{
    use HasCompraTable;

    public static function configure(Table $table): Table
    {
        return self::configureComprasTable($table)
         ->modifyQueryUsing(fn ($query) => $query->where('estado_pago', 'SALDADO')->where('estado', 'FACTURADO'));
    }
}
