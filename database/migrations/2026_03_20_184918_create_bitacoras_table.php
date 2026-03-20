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
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bodega_id')->nullable()->constrained('bodegas')->onDelete('set null');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->enum('tipo', ['evento', 'visita', 'incidente', 'mantenimiento']);
            $table->string('titulo');
            $table->text('descripcion');
            $table->enum('prioridad', ['baja', 'media', 'alta'])->default('baja');
            $table->timestamp('fecha_evento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacoras');
    }
};
