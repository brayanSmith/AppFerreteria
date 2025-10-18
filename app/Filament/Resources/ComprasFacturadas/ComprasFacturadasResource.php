<?php

namespace App\Filament\Resources\ComprasFacturadas;

use App\Filament\Resources\ComprasFacturadas\Pages\CreateComprasFacturadas;
use App\Filament\Resources\ComprasFacturadas\Pages\EditComprasFacturadas;
use App\Filament\Resources\ComprasFacturadas\Pages\ListComprasFacturadas;
use App\Filament\Resources\ComprasFacturadas\Schemas\ComprasFacturadasForm;
use App\Filament\Resources\ComprasFacturadas\Tables\ComprasFacturadasTable;
use App\Models\Compra;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ComprasFacturadasResource extends Resource
{
    protected static ?string $model = Compra::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'factura';
     protected static string|UnitEnum|null $navigationGroup = 'Compras';
     protected static ?string $navigationParentItem = 'Compras';
     protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ComprasFacturadasForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComprasFacturadasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Compras Facturadas';
    }
    public static function getPluralLabel(): string
    {
        return 'Compras Facturadas';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComprasFacturadas::route('/'),
            'create' => CreateComprasFacturadas::route('/create'),
            'edit' => EditComprasFacturadas::route('/{record}/edit'),
        ];
    }
}
