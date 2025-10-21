<?php

namespace App\Filament\Resources\Proveedors;

use App\Filament\Resources\Proveedors\Pages\ManageProveedors;
use App\Models\Proveedor;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ProveedorResource extends Resource
{
    protected static ?string $model = Proveedor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string | UnitEnum | null $navigationGroup = 'Users';

    protected static ?string $recordTitleAttribute = 'razon_social_proveedor';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre_proveedor')
                    ->required()
                    ->maxLength(255),
                TextInput::make('razon_social_proveedor')
                    ->required()
                    ->maxLength(255),
                TextInput::make('nit_proveedor')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('rut_proveedor_imagen')
                    //->required()
                    ->image()
                    ->maxSize(2048),
                Select::make('tipo_proveedor')
                    ->required()
                    ->options([
                        'REMISIONADO' => 'REMISIONADO',
                        'ELECTRONICO' => 'ELECTRONICO',
                    ]),
                Select::make('categoria_proveedor')
                    ->required()
                    ->options([
                        "DECLARANTE" => "DECLARANTE",
                        "NO DECLARANTE" => "NO DECLARANTE",
                        "RETENEDOR" => "RETENEDOR",
                    ]),
                TextInput::make('departamento_proveedor')
                    ->required()
                    ->maxLength(255),
                TextInput::make('ciudad_proveedor')
                    ->required()
                    ->maxLength(255),
                TextInput::make('direccion_proveedor')
                    ->required()
                    ->maxLength(255),
                TextInput::make('telefono_proveedor')
                    ->required()
                    ->maxLength(255),
                TextInput::make('banco_proveedor')
                    ->required()
                    ->maxLength(255),
                Select::make('tipo_cuenta_proveedor')
                    ->required()
                    ->options([
                        'AHORRO' => 'AHORRO',
                        'CORRIENTE' => 'CORRIENTE',
                    ]),
                TextInput::make('numero_cuenta_proveedor')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('razon_social_proveedor')
            ->columns([
                TextColumn::make('razon_social_proveedor')
                    ->searchable(),
                TextColumn::make('nit_proveedor'),
                TextColumn::make('tipo_proveedor'),
                TextColumn::make('categoria_proveedor'),
                TextColumn::make('ciudad_proveedor'),
                TextColumn::make('telefono_proveedor'),
                TextColumn::make('banco_proveedor'),
                TextColumn::make('tipo_cuenta_proveedor'),
                TextColumn::make('numero_cuenta_proveedor'),
                //
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
                    //DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageProveedors::route('/'),
        ];
    }
}
