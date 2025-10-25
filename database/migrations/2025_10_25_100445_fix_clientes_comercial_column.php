<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

public function up(): void
{
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

public function down(): void
{
    Schema::table('clientes', function (Blueprint $table) {
        $table->dropForeign(['comercial_id']);
        $table->dropColumn('comercial_id');

        $table->string('comercial')->nullable();
    });
}

