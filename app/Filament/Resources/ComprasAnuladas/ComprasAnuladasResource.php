<?php

namespace App\Filament\Resources\ComprasAnuladas;

use App\Filament\Resources\ComprasAnuladas\Pages\CreateComprasAnuladas;
use App\Filament\Resources\ComprasAnuladas\Pages\EditComprasAnuladas;
use App\Filament\Resources\ComprasAnuladas\Pages\ListComprasAnuladas;
use App\Filament\Resources\ComprasAnuladas\Schemas\ComprasAnuladasForm;
use App\Filament\Resources\ComprasAnuladas\Tables\ComprasAnuladasTable;
use App\Models\Compra;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ComprasAnuladasResource extends Resource
{
    protected static ?string $model = Compra::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'factura';
    protected static string|UnitEnum|null $navigationGroup = 'Compras';
    protected static ?string $navigationParentItem = 'Compras';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ComprasAnuladasForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComprasAnuladasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Compras Anuladas';
    }
    public static function getPluralLabel(): string
    {
        return 'Compras Anuladas';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComprasAnuladas::route('/'),
            'create' => CreateComprasAnuladas::route('/create'),
            'edit' => EditComprasAnuladas::route('/{record}/edit'),
        ];
    }
}
