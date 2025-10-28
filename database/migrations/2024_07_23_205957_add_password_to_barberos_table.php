<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPasswordToBarberosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barberos', function (Blueprint $table) {
            $table->string('password')->after('email'); // Ajusta 'email' al nombre del campo después del cual deseas agregar 'password'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barberos', function (Blueprint $table) {
            $table->dropColumn('password');
        });
    }
}
