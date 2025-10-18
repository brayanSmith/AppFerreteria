<?php

namespace App\Filament\Resources\ComprasPendientes;

use App\Filament\Resources\ComprasPendientes\Pages\CreateComprasPendientes;
use App\Filament\Resources\ComprasPendientes\Pages\EditComprasPendientes;
use App\Filament\Resources\ComprasPendientes\Pages\ListComprasPendientes;
use App\Filament\Resources\ComprasPendientes\Schemas\ComprasPendientesForm;
use App\Filament\Resources\ComprasPendientes\Tables\ComprasPendientesTable;
use App\Models\Compra;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ComprasPendientesResource extends Resource
{
    protected static ?string $model = Compra::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'factura';
     protected static string|UnitEnum|null $navigationGroup = 'Compras';
     protected static ?string $navigationParentItem = 'Compras';
     protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ComprasPendientesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComprasPendientesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Compras Pendientes';
    }
    public static function getPluralLabel(): string
    {
        return 'Compras Pendientes';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComprasPendientes::route('/'),
            'create' => CreateComprasPendientes::route('/create'),
            'edit' => EditComprasPendientes::route('/{record}/edit'),
        ];
    }
}
