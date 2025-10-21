<?php

namespace App\Filament\Resources\Catalogos;

use App\Filament\Resources\Catalogos\Pages\ManageCatalogos;
use App\Models\Producto;
use BackedEnum;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class CatalogoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'codigo_producto';

    public static function getNavigationLabel(): string
    {
        return 'Catálogo';
    }

    //vamos hacer que el titulo no se muestre
    /*public function getTitle(): string
    {
        return '';
    }*/
    public static function getPluralLabel(): string
    {
        return 'Catálogo';
    }

    /*public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('codigo_producto')
                    ->required()
                    ->maxLength(255),
            ]);
    }*/

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('codigo_producto'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('codigo_producto')
            ->columns([
                Stack::make([
                    TextColumn::make('nombre_producto')
                        ->searchable()
                        ->weight('bold'),
                    ImageColumn::make('imagen_producto')
                        ->label('Imagen')
                        ->disk('public')
                        //->circular()
                        ->height(100)
                        ->width(100)
                        ->alignCenter(),
                    TextColumn::make('codigo_producto')
                        ->searchable()
                        ->extraAttributes([
                            'class' => 'text-xs text-gray-700 font-bold', // tamaño pequeño y gris oscuro
                        ]),

                    TextColumn::make('costo_producto')
                        ->label('Costo')
                        ->formatStateUsing(fn($state) => 'Costo: $' . number_format($state, 0)),
                    //->money('COP', true),
                    TextColumn::make('valor_detal_producto')
                        ->formatStateUsing(fn($state) => 'Detal: $' . number_format($state, 0)),
                    TextColumn::make('valor_mayorista_producto')
                        ->label('Mayorista')
                        ->formatStateUsing(fn($state) => 'Mayorista: $' . number_format($state, 0)),
                    TextColumn::make('valor_ferretero_producto')
                        ->label('Ferretero')
                        ->formatStateUsing(fn($state) => 'Ferretero: $' . number_format($state, 0)),

                ])
            ])
            ->contentGrid([
                'xs' => 2,
                'sm' => 2,
                'md' => 5,
                'lg' => 5,
                'xl' => 5,
            ])
            ->filters([
                //
            ])
            ->recordActions([
                //ViewAction::make(),
                //EditAction::make(),
                //DeleteAction::make(),
            ])
            ->toolbarActions([
                /*BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),*/]);
    }


    public static function getPages(): array
    {
        return [
            'index' => ManageCatalogos::route('/'),
        ];
    }
}
