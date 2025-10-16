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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento');
            $table->string('numero_documento')->unique();
            $table->string('razon_social');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('ciudad');
            $table->string('email')->unique();
            $table->string('representante_legal');
            $table->boolean('activo')->default(true);
            $table->string('novedad')->nullable();
            $table->foreignId('ruta_id')->constrained('rutas')->cascadeOnDelete();
            $table->string('comercial')->nullable();
            $table->enum('tipo_cliente', ['ELECTRONICO', 'REMISIONADO'])->default('ELECTRONICO');
            $table->string('rut_imagen')->nullable();
            $table->enum('retenedor_fuente', ['SI', 'NO'])->default('NO');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
