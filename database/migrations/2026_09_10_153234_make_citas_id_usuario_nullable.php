<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['id_usuario']);
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE citas ALTER COLUMN id_usuario DROP NOT NULL');
        } else {
            DB::statement('ALTER TABLE citas MODIFY COLUMN id_usuario BIGINT UNSIGNED NULL');
        }

        Schema::table('citas', function (Blueprint $table) {
            $table->foreign('id_usuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('UPDATE citas SET id_usuario = (SELECT MIN(id) FROM users) WHERE id_usuario IS NULL');

        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['id_usuario']);
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE citas ALTER COLUMN id_usuario SET NOT NULL');
        } else {
            DB::statement('ALTER TABLE citas MODIFY COLUMN id_usuario BIGINT UNSIGNED NOT NULL');
        }

        Schema::table('citas', function (Blueprint $table) {
            $table->foreign('id_usuario')->references('id')->on('users');
        });
    }
};
