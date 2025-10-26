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
        Schema::table('lecturas_sensores', function (Blueprint $table) {
            // Cambiamos el tipo para permitir 5 dígitos en total, 2 para decimales (hasta 999.99)
            $table->decimal('ec', 5, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lecturas_sensores', function (Blueprint $table) {
            //
        });
    }
};
