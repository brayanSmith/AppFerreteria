<?php

namespace App\Filament\Resources\PedidosEstadoPagoSaldados;

use App\Filament\Resources\PedidosEstadoPagoSaldados\Pages\CreatePedidosEstadoPagoSaldado;
use App\Filament\Resources\PedidosEstadoPagoSaldados\Pages\EditPedidosEstadoPagoSaldado;
use App\Filament\Resources\PedidosEstadoPagoSaldados\Pages\ListPedidosEstadoPagoSaldados;
use App\Filament\Resources\PedidosEstadoPagoSaldados\Schemas\PedidosEstadoPagoSaldadoForm;
use App\Filament\Resources\PedidosEstadoPagoSaldados\Tables\PedidosEstadoPagoSaldadosTable;
use App\Models\Pedido;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PedidosEstadoPagoSaldadoResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Cartera Ventas';

    protected static ?string $recordTitleAttribute = 'codigo';

    public static function form(Schema $schema): Schema
    {
        return PedidosEstadoPagoSaldadoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PedidosEstadoPagoSaldadosTable::configure($table);
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
            'index' => ListPedidosEstadoPagoSaldados::route('/'),
            //'create' => CreatePedidosEstadoPagoSaldado::route('/create'),
            'edit' => EditPedidosEstadoPagoSaldado::route('/{record}/edit'),
        ];
    }
}
