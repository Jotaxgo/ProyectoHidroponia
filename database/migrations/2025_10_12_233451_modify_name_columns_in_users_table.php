<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name'); // Eliminamos la columna 'name'
            $table->string('nombres')->after('id');
            $table->string('primer_apellido')->after('nombres');
            $table->string('segundo_apellido')->nullable()->after('primer_apellido');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->dropColumn(['nombres', 'primer_apellido', 'segundo_apellido']);
        });
    }
};