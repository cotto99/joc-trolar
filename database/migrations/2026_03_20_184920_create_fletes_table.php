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
        Schema::create('fletes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bodega_id')->nullable()->constrained('bodegas')->onDelete('set null');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->string('descripcion_carga');
            $table->decimal('costo', 10, 2);
            $table->date('fecha_flete');
            $table->enum('estado', ['pendiente', 'en_camino', 'entregado'])->default('pendiente');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fletes');
    }
};
