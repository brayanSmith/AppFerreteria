<?php

namespace App\Filament\Resources\Bodegas;

use App\Filament\Resources\Bodegas\Pages\ManageBodegas;
use App\Models\Bodega;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class BodegaResource extends Resource
{
    protected static ?string $model = Bodega::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Productos';

    protected static ?string $recordTitleAttribute = 'nombre_bodega';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre_bodega')
                    ->required()
                    ->unique(),
                TextInput::make('ubicacion_bodega')
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre_bodega')
            ->columns([
                TextColumn::make('nombre_bodega')
                    ->searchable(),
                TextColumn::make('ubicacion_bodega')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageBodegas::route('/'),
        ];
    }
}
