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
        Schema::table('clientes', function (Blueprint $table) {
            // Eliminar la columna comercial solo si existe
            /*if (Schema::hasColumn('clientes', 'comercial')) {
                $table->dropColumn('comercial');
            }

            // Agregar comercial_id solo si no existe
            if (!Schema::hasColumn('clientes', 'comercial_id')) {
                $table->foreignId('comercial_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }*/
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Verificar si la clave foránea existe antes de eliminarla
            if (Schema::hasColumn('clientes', 'comercial_id')) {
                // Intentar eliminar la clave foránea solo si existe
                try {
                    $table->dropForeign(['comercial_id']);
                } catch (\Exception $e) {
                    // Si no existe la clave foránea, continúa sin error
                }
                $table->dropColumn('comercial_id');
            }

            // Agregar la columna comercial solo si no existe
            if (!Schema::hasColumn('clientes', 'comercial')) {
                $table->string('comercial')->nullable();
            }
        });
    }
};
