<?php

namespace App\Filament\Resources\ComprasEstadoEnCarteras;

use App\Filament\Resources\ComprasEstadoEnCarteras\Pages\CreateComprasEstadoEnCartera;
use App\Filament\Resources\ComprasEstadoEnCarteras\Pages\EditComprasEstadoEnCartera;
use App\Filament\Resources\ComprasEstadoEnCarteras\Pages\ListComprasEstadoEnCarteras;
use App\Filament\Resources\ComprasEstadoEnCarteras\Schemas\ComprasEstadoEnCarteraForm;
use App\Filament\Resources\ComprasEstadoEnCarteras\Tables\ComprasEstadoEnCarterasTable;
use App\Models\Compra;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ComprasEstadoEnCarteraResource extends Resource
{
    protected static ?string $model = Compra::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'factura';
        protected static string|UnitEnum|null $navigationGroup = 'Cartera Compras';
        //protected static ?string $navigationParentItem = 'Compras';
        //protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ComprasEstadoEnCarteraForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComprasEstadoEnCarterasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Cuentas por Pagar';
    }
    public static function getPluralLabel(): string
    {
        return 'Cuentas por Pagar';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComprasEstadoEnCarteras::route('/'),
            'create' => CreateComprasEstadoEnCartera::route('/create'),
            'edit' => EditComprasEstadoEnCartera::route('/{record}/edit'),
        ];
    }
}
