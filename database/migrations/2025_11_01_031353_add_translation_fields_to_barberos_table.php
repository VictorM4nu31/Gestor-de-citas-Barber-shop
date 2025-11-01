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
        Schema::table('barberos', function (Blueprint $table) {
            // Add English translation fields
            $table->string('especialidad_en')->nullable()->after('especialidad');
            $table->text('experiencia_en')->nullable()->after('experiencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barberos', function (Blueprint $table) {
            $table->dropColumn(['especialidad_en', 'experiencia_en']);
        });
    }
};
