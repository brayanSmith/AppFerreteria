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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique()->nullable();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->dateTime('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('dias_plazo_vencimiento')->default(30);
            $table->date('fecha_vencimiento')->nullable();
            $table->string('ciudad')->nullable();
            $table->enum('estado', ['PENDIENTE', 'FACTURADO', 'ANULADO'])->default(value: 'PENDIENTE');
            $table->boolean('stock_retirado')->default(false);
            $table->boolean('en_cartera')->default(false);
            $table->enum('metodo_pago', ['CREDITO', 'CONTADO'])->default('CREDITO');
            $table->enum('tipo_precio', ['FERRETERO','MAYORISTA', 'DETAL'])->default('FERRETERO');
            $table->enum('tipo_venta', ['ELECTRONICA','REMISIONADA'])->default('REMISIONADA');
            $table->enum('estado_pago', ['EN_CARTERA', 'SALDADO'])->default('EN_CARTERA');
            $table->text('primer_comentario')->nullable();
            $table->text('segundo_comentario')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('abono', 12, 2)->default(0);
            $table->decimal('descuento', 12, 2)->default(0)->nullable();
            $table->decimal('total_a_pagar', 12, 2)->default(0);
            $table->integer('contador_impresiones')->default(0);
            $table->boolean('impresa')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
