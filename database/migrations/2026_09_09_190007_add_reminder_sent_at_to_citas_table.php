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
        Schema::table('citas', function (Blueprint $table) {
            $table->timestamp('recordatorio_enviado_at')->nullable()->after('fecha_atencion');
            $table->index(['fecha', 'estado', 'recordatorio_enviado_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropIndex(['fecha', 'estado', 'recordatorio_enviado_at']);
            $table->dropColumn('recordatorio_enviado_at');
        });
    }
};
