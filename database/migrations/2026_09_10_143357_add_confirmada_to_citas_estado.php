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
        if (DB::getDriverName() === 'pgsql') {
            $this->replaceEstadoCheck(['pendiente', 'confirmada', 'atendida', 'cancelada']);

            return;
        }

        DB::statement("ALTER TABLE citas MODIFY COLUMN estado ENUM('pendiente', 'confirmada', 'atendida', 'cancelada') NOT NULL DEFAULT 'pendiente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE citas SET estado = 'pendiente' WHERE estado = 'confirmada'");

        if (DB::getDriverName() === 'pgsql') {
            $this->replaceEstadoCheck(['pendiente', 'atendida', 'cancelada']);

            return;
        }

        DB::statement("ALTER TABLE citas MODIFY COLUMN estado ENUM('pendiente', 'atendida', 'cancelada') NOT NULL DEFAULT 'pendiente'");
    }

    /**
     * Recreate the CHECK constraint Laravel generated for the enum column on PostgreSQL.
     *
     * @param  array<int, string>  $allowed
     */
    private function replaceEstadoCheck(array $allowed): void
    {
        $list = implode(', ', array_map(fn (string $value): string => "'{$value}'", $allowed));

        DB::statement('ALTER TABLE citas DROP CONSTRAINT IF EXISTS citas_estado_check');
        DB::statement("ALTER TABLE citas ADD CONSTRAINT citas_estado_check CHECK (estado IN ({$list}))");
    }
};
