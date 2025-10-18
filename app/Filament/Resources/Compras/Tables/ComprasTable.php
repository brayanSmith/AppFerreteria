<?php

namespace App\Filament\Resources\Compras\Tables;

use Filament\Tables\Table;
use App\Filament\Resources\Compras\Tables\Concerns\HasCompraTable;

class ComprasTable
{
    use HasCompraTable;

    public static function configure(Table $table): Table
    {
        return self::configureComprasTable($table);
    }
}
