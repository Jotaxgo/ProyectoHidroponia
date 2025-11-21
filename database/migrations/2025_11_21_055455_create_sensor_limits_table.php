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
        Schema::create('sensor_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vivero_id')->constrained()->onDelete('cascade');
            $table->string('sensor'); // e.g., 'ph', 'ec', 'temperatura', 'luz', 'humedad'
            $table->float('min', 8, 2)->nullable();
            $table->float('max', 8, 2)->nullable();
            $table->timestamps();

            $table->unique(['vivero_id', 'sensor']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_limits');
    }
};