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
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            
            // Relación con el vivero
            $table->foreignId('vivero_id')->constrained('viveros')->onDelete('cascade');
            
            // Información descriptiva
            $table->string('codigo_identificador')->unique();
            $table->string('tipo_sistema'); // Ej: 'NFT', 'DWC'
            $table->integer('capacidad');
            
            // Información de estado y cultivo
            $table->string('estado')->default('Disponible');
            $table->string('cultivo_actual')->nullable();
            $table->date('fecha_siembra')->nullable();

            // Información técnica (opcional pero muy recomendado)
            $table->json('hardware_info')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};
