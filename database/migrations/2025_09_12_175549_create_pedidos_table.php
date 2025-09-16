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
            $table->foreignId('cliente_id')->constrained('clientes');
            //$table->dateTime('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('estado', ['BORRADOR','PAGADO','ANULADO'])->default('BORRADOR');
            $table->enum('metodo_pago', ['A CREDITO', 'EFECTIVO'])->default('A CREDITO');
            $table->enum('tipo_precio', ['FERRETERO','MAYORISTA', 'DETAL'])->default('DETAL');
            $table->text('primer_comentario')->nullable();
            $table->text('segundo_comentario')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
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
