<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitasTable extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo');
            $table->string('numero_telefono');
            $table->string('correo_electronico');
            $table->date('fecha');
            $table->time('hora');
            $table->unsignedBigInteger('id_servicio');
            $table->unsignedBigInteger('id_barbero');
            $table->timestamps();

            // Definir las relaciones con las tablas de servicios y barberos
            $table->foreign('id_servicio')->references('id')->on('servicios')->onDelete('cascade');
            $table->foreign('id_barbero')->references('id')->on('barberos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
}

