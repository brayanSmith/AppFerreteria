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
        Schema::create('proveedors', function (Blueprint $table) {
            $table->id();
            $table->string('nit_proveedor')->unique();
            $table->string('nombre_proveedor');
            $table->string('ciudad_proveedor')->nullable();
            $table->string('direccion_proveedor')->nullable();
            $table->string('telefono_proveedor')->nullable();
            $table->enum('tipo_proveedor', ['REMISIONADO', 'ELECTRONICO'])->default('REMISIONADO');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedors');
    }
};
