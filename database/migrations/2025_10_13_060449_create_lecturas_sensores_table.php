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
        Schema::create('lecturas_sensores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('modulos')->onDelete('cascade');
            $table->decimal('temperatura', 5, 2)->nullable();
            $table->decimal('ph', 4, 2)->nullable();
            $table->decimal('humedad', 5, 2)->nullable();
            // Aquí puedes añadir más columnas para otros sensores en el futuro
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturas_sensores');
    }
};
