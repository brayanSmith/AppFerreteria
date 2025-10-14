<?php

namespace App\Filament\Resources\PedidosPendientes;

use App\Filament\Resources\PedidosPendientes\Pages\CreatePedidosPendientes;
use App\Filament\Resources\PedidosPendientes\Pages\EditPedidosPendientes;
use App\Filament\Resources\PedidosPendientes\Pages\ListPedidosPendientes;
use App\Filament\Resources\PedidosPendientes\Schemas\PedidosPendientesForm;
use App\Filament\Resources\PedidosPendientes\Tables\PedidosPendientesTable;
use App\Models\Pedido;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PedidosPendientesResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;


    protected static string|UnitEnum|null $navigationGroup = 'Ventas';

    protected static ?string $recordTitleAttribute = 'codigo';

    public static function form(Schema $schema): Schema
    {
        return PedidosPendientesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PedidosPendientesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Pedidos Pendientes';
    }
    public static function getPluralLabel(): string
    {
        return 'Pedidos Pendientes';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPedidosPendientes::route('/'),
            //'create' => CreatePedidosPendientes::route('/create'),
            'edit' => EditPedidosPendientes::route('/{record}/edit'),
        ];
    }
}
