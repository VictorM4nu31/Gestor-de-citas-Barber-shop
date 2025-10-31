<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Barbero;
use App\Models\Servicio;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Crear tabla pivot barbero_servicio
        Schema::create('barbero_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbero_id')->constrained('barberos')->onDelete('cascade');
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->timestamps();
            
            // Índice único compuesto para evitar duplicados
            $table->unique(['barbero_id', 'servicio_id'], 'unique_barbero_servicio');
        });

        // Migrar datos existentes: asignar todos los servicios publicados a barberos activos
        $this->migrateExistingData();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barbero_servicio');
    }

    /**
     * Migra datos existentes asignando servicios publicados a barberos activos
     */
    private function migrateExistingData(): void
    {
        try {
            // Obtener barberos activos
            $barberosActivos = Barbero::where('activo', true)->get();
            
            // Obtener servicios publicados
            $serviciosPublicados = Servicio::where('publicado', true)->pluck('id');

            if ($barberosActivos->isEmpty()) {
                echo "\n⚠️  No hay barberos activos para asignar servicios.\n";
                return;
            }

            if ($serviciosPublicados->isEmpty()) {
                echo "\n⚠️  No hay servicios publicados para asignar.\n";
                return;
            }

            $totalAsignaciones = 0;

            // Asignar todos los servicios publicados a cada barbero activo
            foreach ($barberosActivos as $barbero) {
                foreach ($serviciosPublicados as $servicioId) {
                    \DB::table('barbero_servicio')->insert([
                        'barbero_id' => $barbero->id,
                        'servicio_id' => $servicioId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $totalAsignaciones++;
                }
            }

            echo "\n✅ Migración de datos completada:\n";
            echo "   - Barberos activos: {$barberosActivos->count()}\n";
            echo "   - Servicios publicados: {$serviciosPublicados->count()}\n";
            echo "   - Total asignaciones creadas: {$totalAsignaciones}\n\n";

        } catch (\Exception $e) {
            echo "\n❌ Error durante la migración de datos: " . $e->getMessage() . "\n";
            echo "   Los datos se pueden migrar manualmente después de la migración.\n\n";
        }
    }
};
