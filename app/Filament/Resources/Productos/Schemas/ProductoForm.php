<?php

namespace App\Filament\Resources\Productos\Schemas;

use App\Models\SubCategoria;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;


class ProductoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('codigo_producto')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('Ingrese el código del producto'),
                TextInput::make('nombre_producto')
                    ->required()
                    ->placeholder('Ingrese el nombre del producto'),
                TextArea::make('descripcion_producto')
                    ->default(null),

                // Padre: categoría
                Select::make('categoria_id')
                    ->label('Categoría')
                    ->relationship('categoria', 'nombre_categoria')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live() // refresca al cambiar
                    ->afterStateUpdated(fn(Set $set) => $set('sub_categoria_id', null)),

                // Hijo: subcategoría filtrada por categoría
                Select::make('sub_categoria_id')
                    ->label('Subcategoría')
                    ->relationship(
                        name: 'subCategoria',                    // relación en tu modelo Producto
                        titleAttribute: 'nombre_sub_categoria',
                        modifyQueryUsing: fn(EloquentBuilder $query, Get $get) =>
                        $query->when($get('categoria_id'), fn($q, $cat) => $q->where('categoria_id', $cat)),
                    )
                    ->disabled(fn(Get $get) => blank($get('categoria_id')))
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('costo_producto')
                    ->required()
                    ->inputMode('decimal')
                    ->numeric(),
                TextInput::make('valor_detal_producto')
                    ->required()
                    ->inputMode('decimal')
                    ->numeric(),
                TextInput::make('valor_mayorista_producto')
                    ->required()
                    ->inputMode('decimal')
                    ->numeric(),
                TextInput::make('valor_ferretero_producto')
                    ->required()
                    ->numeric(),

                FileUpload::make('imagen_producto')
                    ->label('Seleccione una imagen')
                    ->image()
                    ->directory('productos')
                    ->disk('public')
                    ->imageEditor()
                    ->downloadable()
                    ->openable()
                    ->nullable()
                    ->maxSize(1024) // 1MB
                    ->default(null),


                Select::make('bodega_id')
                    ->label('Bodega')
                    ->relationship('bodega', 'nombre_bodega')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('stock')
                    ->label('Stock')
                    ->required()
                    ->numeric()
                    ->default(0),

                Toggle::make('activo')
                    ->required()
                    ->default(true),

            ]);
    }
}
