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
        Schema::create('gastos_variables', function (Blueprint $table) {
            $table->id();
            $table->string('categoria')->nullable(); // limpieza, reparacion, etc
            $table->string('descripcion');
            $table->decimal('monto', 10, 2);
            $table->date('fecha_gasto');
            $table->string('comprobante')->nullable(); // número de factura o referencia
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos_variables');
    }
};
