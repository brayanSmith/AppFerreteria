<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medidas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_medida')->unique();
            $table->string('descripcion_medida')->nullable();
            $table->timestamps();
        });

        Schema::create('bodegas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_bodega')->unique();
            $table->string('ubicacion_bodega')->nullable();
            $table->timestamps();
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_categoria')->unique();
            $table->timestamps();
        });

        Schema::create('sub_categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_sub_categoria')->unique();
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_producto')->unique();
            $table->string('nombre_producto');
            $table->string('descripcion_producto')->nullable();
            $table->decimal('costo_producto', 10, 2);
            $table->decimal('valor_detal_producto', 10, 2);
            $table->decimal('valor_mayorista_producto', 10, 2);
            $table->decimal('valor_ferretero_producto', 10, 2);
            $table->string('imagen_producto')->nullable();
            $table->foreignId('bodega_id')->constrained('bodegas')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('sub_categoria_id')->constrained('sub_categorias')->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('formulas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_formula')->unique();
            $table->text('descripcion_formula')->nullable();
            $table->timestamps();
        });

        Schema::create('produccions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formula_id')->constrained('formulas')->onDelete('cascade');
            $table->integer('cantidad');
            $table->string('lote');
            $table->date('fecha_produccion');
            $table->date('fecha_caducidad');
            $table->text('Observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('detalle_produccions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produccion_id')->constrained('produccions')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('medida_id')->constrained('medidas')->onDelete('cascade');
            $table->date('fecha_produccion');
            $table->text('Observaciones')->nullable();
            $table->decimal('cantidad', 10, 2);
            $table->string('lote')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_produccions');
        Schema::dropIfExists('produccions');
        Schema::dropIfExists('formulas');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('sub_categorias');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('bodegas');
        Schema::dropIfExists('medidas');
    }
};
