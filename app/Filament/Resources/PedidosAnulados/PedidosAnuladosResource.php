<?php

namespace App\Filament\Resources\PedidosAnulados;

use App\Filament\Resources\PedidosAnulados\Pages\CreatePedidosAnulados;
use App\Filament\Resources\PedidosAnulados\Pages\EditPedidosAnulados;
use App\Filament\Resources\PedidosAnulados\Pages\ListPedidosAnulados;
use App\Filament\Resources\PedidosAnulados\Schemas\PedidosAnuladosForm;
use App\Filament\Resources\PedidosAnulados\Tables\PedidosAnuladosTable;
use App\Models\Pedido;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PedidosAnuladosResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Ventas';

    protected static ?string $recordTitleAttribute = 'codigo';

    public static function form(Schema $schema): Schema
    {
        return PedidosAnuladosForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PedidosAnuladosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Pedidos Anulados';
    }
    public static function getPluralLabel(): string
    {
        return 'Pedidos Anulados';
    }
    public static function getPages(): array
    {
        return [
            'index' => ListPedidosAnulados::route('/'),
            'create' => CreatePedidosAnulados::route('/create'),
            'edit' => EditPedidosAnulados::route('/{record}/edit'),
        ];
    }
}
