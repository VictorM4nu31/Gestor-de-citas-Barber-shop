<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarberosTable extends Migration
{
    public function up(): void
    {
        Schema::create('barberos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo');
            $table->string('email')->unique();
            $table->string('telefono');
            $table->string('especialidad');
            $table->integer('experiencia'); // en años
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barberos');
    }
}

