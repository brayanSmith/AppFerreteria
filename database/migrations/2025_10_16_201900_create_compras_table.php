<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->string('factura')->unique();
            $table->foreignId('proveedor_id')->constrained('proveedors');
            $table->dateTime('fecha')->nullable();
            $table->integer('dias_plazo_vencimiento')->default(30);
            $table->date('fecha_vencimiento')->nullable();
            $table->enum('metodo_pago', ['CREDITO', 'CONTADO'])->default('CREDITO');
            $table->enum('estado_pago', ['EN_CARTERA', 'SALDADO'])->default('EN_CARTERA');
            $table->boolean('stock_sumado')->default(false);
            $table->enum('tipo_compra', ['ELECTRONICA','REMISIONADA'])->default('REMISIONADA');
            $table->enum('estado', ['PENDIENTE', 'FACTURADO', 'ANULADO'])->default(value: 'PENDIENTE');
            $table->text('observaciones')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('abono', 12, 2)->default(0)->nullable();
            $table->decimal('descuento', 12, 2)->default(0)->nullable();
            $table->decimal('total_a_pagar', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
