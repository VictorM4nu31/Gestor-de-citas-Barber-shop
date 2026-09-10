<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE citas MODIFY COLUMN estado ENUM('pendiente', 'confirmada', 'atendida', 'cancelada') NOT NULL DEFAULT 'pendiente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE citas SET estado = 'pendiente' WHERE estado = 'confirmada'");
        DB::statement("ALTER TABLE citas MODIFY COLUMN estado ENUM('pendiente', 'atendida', 'cancelada') NOT NULL DEFAULT 'pendiente'");
    }
};
