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
        Schema::create('gastos_fijos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // agua, luz, seguridad...
            $table->text('descripcion')->nullable();
            $table->decimal('monto_mensual', 10, 2);
            $table->integer('dia_pago')->default(1);
            $table->boolean('activo')->default(true);
            $table->text('justificacion_inactivo')->nullable(); // razón si se desactiva
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos_fijos');
    }
};
