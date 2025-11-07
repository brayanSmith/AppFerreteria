<?php

namespace App\Filament\Resources\Empresas;

use App\Filament\Resources\Empresas\Pages\ManageEmpresas;
use App\Models\Empresa;
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
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;

class EmpresaResource extends Resource
{
    protected static ?string $model = Empresa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nombre_empresa';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre_empresa')
                    ->required()
                    ->maxLength(255),
                TextInput::make('direccion_empresa')
                    ->maxLength(255),
                TextInput::make('telefono_empresa')
                    ->maxLength(255),
                TextInput::make('email_empresa')
                    ->maxLength(255),
                TextInput::make('nit_empresa')
                    ->maxLength(255),                

                FileUpload::make('logo_empresa')
                    ->label('Seleccione una imagen')
                    ->image()
                    ->directory('logos_empresas')
                    ->disk('public')
                    ->imageEditor()
                    ->downloadable()
                    ->openable()
                    ->nullable()
                    ->maxSize(1024) // 1MB
                    ->default(null),
            ]); 
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre_empresa')
            ->columns([
                ImageColumn::make('logo_empresa')
                    ->label('Logo')
                    ->disk('public')
                    ->circular()
                    ->size(50),
                TextColumn::make('nombre_empresa')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                //DeleteAction::make(),
            ])
            ->toolbarActions([
                /*BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),*/
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEmpresas::route('/'),
        ];
    }
}
