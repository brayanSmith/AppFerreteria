<?php

namespace App\Filament\Resources\SubCategorias;

use App\Filament\Resources\SubCategorias\Pages\ManageSubCategorias;
use App\Models\SubCategoria;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubCategoriaResource extends Resource
{
    protected static ?string $model = SubCategoria::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nombre_sub_categoria';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre_sub_categoria')
                    ->required(),
                Select::make('categoria_id')
                    ->label('Categoría')
                    ->relationship('categoria', 'nombre_categoria')
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre_sub_categoria')
            ->columns([
                TextColumn::make('nombre_sub_categoria')
                    ->searchable(),
                TextColumn::make('categoria.nombre_categoria')
                    ->label('Categoría')
                    ->searchable()
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ManageSubCategorias::route('/'),
        ];
    }
}
