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
            $table->decimal('ec', 4, 2)->nullable()->after('ph');
            $table->integer('luz')->nullable()->after('ec');
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
