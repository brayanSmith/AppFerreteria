<?php

namespace App\Filament\Resources\PedidosEstadoPagoEnCarteras;

use App\Filament\Resources\PedidosEstadoPagoEnCarteras\Pages\CreatePedidosEstadoPagoEnCartera;
use App\Filament\Resources\PedidosEstadoPagoEnCarteras\Pages\EditPedidosEstadoPagoEnCartera;
use App\Filament\Resources\PedidosEstadoPagoEnCarteras\Pages\ListPedidosEstadoPagoEnCarteras;
use App\Filament\Resources\PedidosEstadoPagoEnCarteras\Schemas\PedidosEstadoPagoEnCarteraForm;
use App\Filament\Resources\PedidosEstadoPagoEnCarteras\Tables\PedidosEstadoPagoEnCarterasTable;
use App\Models\Pedido;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PedidosEstadoPagoEnCarteraResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Ventas';

    protected static ?string $recordTitleAttribute = 'codigo';

    public static function form(Schema $schema): Schema
    {
        return PedidosEstadoPagoEnCarteraForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PedidosEstadoPagoEnCarterasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getNavigationLabel(): string
    {
        return 'Pedidos Estado Pago en Cartera';
    }
    public static function getPluralLabel(): string
    {
        return 'Pedidos Estado Pago en Cartera';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPedidosEstadoPagoEnCarteras::route('/'),
            //'create' => CreatePedidosEstadoPagoEnCartera::route('/create'),
            'edit' => EditPedidosEstadoPagoEnCartera::route('/{record}/edit'),
        ];
    }
}
