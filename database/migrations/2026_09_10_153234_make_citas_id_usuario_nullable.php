<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('citas', function ($table) {
            $table->dropForeign(['id_usuario']);
        });
        DB::statement('ALTER TABLE citas MODIFY COLUMN id_usuario BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE citas ADD CONSTRAINT citas_id_usuario_foreign FOREIGN KEY (id_usuario) REFERENCES users (id)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('UPDATE citas SET id_usuario = (SELECT MIN(id) FROM users) WHERE id_usuario IS NULL');
        Schema::table('citas', function ($table) {
            $table->dropForeign(['id_usuario']);
        });
        DB::statement('ALTER TABLE citas MODIFY COLUMN id_usuario BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE citas ADD CONSTRAINT citas_id_usuario_foreign FOREIGN KEY (id_usuario) REFERENCES users (id)');
    }
};
