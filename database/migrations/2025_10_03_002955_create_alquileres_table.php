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
        Schema::create('alquileres', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users');
        $table->foreignId('vivero_id')->constrained('viveros');
        // Dejaremos 'modulo_id' pendiente hasta que creemos la tabla modulos
        $table->date('fecha_inicio');
        $table->date('fecha_fin');
        $table->decimal('costo', 8, 2); // 8 dígitos en total, 2 para decimales
        $table->string('estado')->default('Activo'); // Valor por defecto
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alquileres');
    }
};
