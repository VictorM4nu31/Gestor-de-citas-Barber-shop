<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitasTable extends Migration
{
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo');
            $table->string('numero_telefono');
            $table->string('correo_electronico');
            $table->date('fecha');
            $table->time('hora');
            $table->text('servicios'); // Guardar los servicios seleccionados como texto concatenado
            $table->foreignId('id_barbero')->constrained('barberos')->onDelete('cascade');
            $table->foreignId('id_usuario')->constrained('users'); // Asegúrate de tener una tabla 'users'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('citas');
    }
}
