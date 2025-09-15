<?php

namespace App\Filament\Resources\Ingredientes;

use App\Filament\Resources\Ingredientes\Pages\CreateIngrediente;
use App\Filament\Resources\Ingredientes\Pages\EditIngrediente;
use App\Filament\Resources\Ingredientes\Pages\ListIngredientes;
use App\Filament\Resources\Ingredientes\Pages\ViewIngrediente;
use App\Filament\Resources\Ingredientes\Schemas\IngredienteForm;
use App\Filament\Resources\Ingredientes\Schemas\IngredienteInfolist;
use App\Filament\Resources\Ingredientes\Tables\IngredientesTable;
use App\Models\Ingrediente;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IngredienteResource extends Resource
{
    protected static ?string $model = Ingrediente::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'codigo';

    public static function form(Schema $schema): Schema
    {
        return IngredienteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return IngredienteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IngredientesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIngredientes::route('/'),
            'create' => CreateIngrediente::route('/create'),
            'view' => ViewIngrediente::route('/{record}'),
            'edit' => EditIngrediente::route('/{record}/edit'),
        ];
    }
}
