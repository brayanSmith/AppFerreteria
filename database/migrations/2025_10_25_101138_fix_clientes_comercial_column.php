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
        //
        Schema::table('clientes', function (Blueprint $table) {
        if (Schema::hasColumn('clientes', 'comercial')) {
            $table->dropColumn('comercial');
        }

        $table->foreignId('comercial_id')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('clientes', function (Blueprint $table) {
        $table->dropForeign(['comercial_id']);
        $table->dropColumn('comercial_id');

        $table->string('comercial')->nullable();
    });
    }
};
