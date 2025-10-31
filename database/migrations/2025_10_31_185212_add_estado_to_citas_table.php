<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->enum('estado', ['pendiente', 'atendida', 'cancelada'])->default('pendiente')->after('costo');
            $table->timestamp('fecha_atencion')->nullable()->after('estado');
        });
    }

    public function down()
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn(['estado', 'fecha_atencion']);
        });
    }
};