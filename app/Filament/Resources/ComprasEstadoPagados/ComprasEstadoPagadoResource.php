<?php

namespace App\Filament\Resources\ComprasEstadoPagados;

use App\Filament\Resources\ComprasEstadoPagados\Pages\CreateComprasEstadoPagado;
use App\Filament\Resources\ComprasEstadoPagados\Pages\EditComprasEstadoPagado;
use App\Filament\Resources\ComprasEstadoPagados\Pages\ListComprasEstadoPagados;
use App\Filament\Resources\ComprasEstadoPagados\Schemas\ComprasEstadoPagadoForm;
use App\Filament\Resources\ComprasEstadoPagados\Tables\ComprasEstadoPagadosTable;
use App\Models\Compra;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ComprasEstadoPagadoResource extends Resource
{
    protected static ?string $model = Compra::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'factura';
         protected static string|UnitEnum|null $navigationGroup = 'Cartera Compras';
        //protected static ?string $navigationParentItem = 'Compras';
        //protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ComprasEstadoPagadoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComprasEstadoPagadosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Cuentas Pagadas';
    }
    public static function getPluralLabel(): string
    {
        return 'Cuentas Pagadas';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComprasEstadoPagados::route('/'),
            'create' => CreateComprasEstadoPagado::route('/create'),
            'edit' => EditComprasEstadoPagado::route('/{record}/edit'),
        ];
    }
}
