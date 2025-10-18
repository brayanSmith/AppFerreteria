<?php

namespace App\Filament\Resources\ComprasAnuladas\Tables;

use App\Filament\Resources\Compras\Tables\Concerns\HasCompraTable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ComprasAnuladasTable
{
    use HasCompraTable;

    public static function configure(Table $table): Table
    {
        return self::configureComprasTable($table)
        //Vamos hacer que nos traiga solo las que tengan estado anulada
         ->modifyQueryUsing(fn($query) => $query->where('estado', 'ANULADO'));
    }
}
