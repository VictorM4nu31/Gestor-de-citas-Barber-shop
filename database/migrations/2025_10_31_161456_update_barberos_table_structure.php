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
        Schema::table('barberos', function (Blueprint $table) {
            // Eliminar campo password redundante si existe
            if (Schema::hasColumn('barberos', 'password')) {
                $table->dropColumn('password');
            }

            // Agregar campos activo (boolean) y fecha_baja (timestamp)
            $table->boolean('activo')->default(true)->after('foto');
            $table->timestamp('fecha_baja')->nullable()->after('activo');
        });

        // Verificar si hay barberos con user_id null y mostrar advertencia
        $barberosWithoutUser = DB::table('barberos')->whereNull('user_id')->count();

        if ($barberosWithoutUser > 0) {
            echo "\n⚠️  ADVERTENCIA: Hay {$barberosWithoutUser} barberos sin user_id.\n";
            echo "   Ejecute la migración de datos (tarea 2) antes de hacer user_id obligatorio.\n";
            echo "   Por ahora, user_id permanece nullable hasta que se complete la migración de datos.\n\n";
        } else {
            // Solo hacer user_id NOT NULL si no hay registros problemáticos
            Schema::table('barberos', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable(false)->change();
            });
            echo "\n✅ user_id ahora es obligatorio (NOT NULL)\n\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barberos', function (Blueprint $table) {
            // Revertir cambios: hacer user_id nullable nuevamente
            $table->foreignId('user_id')->nullable()->change();

            // Eliminar campos agregados
            $table->dropColumn(['activo', 'fecha_baja']);

            // Restaurar campo password si se eliminó
            // Nota: No podemos restaurar datos perdidos, solo la estructura
            $table->string('password')->nullable()->after('email');
        });
    }
};
