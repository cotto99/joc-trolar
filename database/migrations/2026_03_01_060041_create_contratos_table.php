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
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('bodega_id')->constrained('bodegas')->onDelete('cascade');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('monto', 10, 2);
            $table->enum('periodicidad', ['mensual', 'trimestral', 'semestral', 'anual']);
            $table->integer('dia_pago')->default(1); // día del mes que se paga
            $table->enum('estado', ['activo', 'vencido', 'cancelado'])->default('activo');
            $table->text('observaciones')->nullable();
            $table->string('numero_contrato')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
