<?php

use App\Models\Barbero;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ejecutar en una transacción para mantener integridad de datos
        DB::transaction(function () {
            $this->migrateExistingBarberos();
            $this->assignBarberoRoles();
            $this->synchronizeEmails();
            $this->verifyDataIntegrity();
        });
    }

    /**
     * Crear usuarios para barberos existentes que no los tengan
     */
    private function migrateExistingBarberos(): void
    {
        $barberosWithoutUsers = Barbero::whereNull('user_id')->get();

        foreach ($barberosWithoutUsers as $barbero) {
            // Verificar si ya existe un usuario con el mismo email
            $existingUser = User::where('email', $barbero->email)->first();

            if ($existingUser) {
                // Si existe un usuario con el mismo email, vincularlo
                $barbero->update(['user_id' => $existingUser->id]);
                echo "Vinculado barbero {$barbero->id} con usuario existente {$existingUser->id}\n";
            } else {
                // Crear nuevo usuario para el barbero
                $user = User::create([
                    'name' => $barbero->nombre_completo,
                    'email' => $barbero->email,
                    'password' => Hash::make('password123'), // Contraseña temporal
                    'email_verified_at' => now(),
                ]);

                // Vincular barbero con el nuevo usuario
                $barbero->update(['user_id' => $user->id]);
                echo "Creado usuario {$user->id} para barbero {$barbero->id}\n";
            }
        }
    }

    /**
     * Asignar rol "barbero" a todos los usuarios de barberos
     */
    private function assignBarberoRoles(): void
    {
        $barberoRole = Role::where('name', 'barbero')->first();

        if (! $barberoRole) {
            // Crear el rol si no existe (para compatibilidad con testing y desarrollo)
            $barberoRole = Role::firstOrCreate(['name' => 'barbero']);
            echo "Rol 'barbero' creado o encontrado\n";
        }

        $barberos = Barbero::whereNotNull('user_id')->with('user')->get();

        foreach ($barberos as $barbero) {
            if ($barbero->user && ! $barbero->user->hasRole('barbero')) {
                $barbero->user->assignRole('barbero');
                echo "Asignado rol barbero a usuario {$barbero->user->id}\n";
            }
        }
    }

    /**
     * Sincronizar emails entre tablas users y barberos
     */
    private function synchronizeEmails(): void
    {
        $barberos = Barbero::whereNotNull('user_id')->with('user')->get();

        foreach ($barberos as $barbero) {
            if ($barbero->user && $barbero->email !== $barbero->user->email) {
                // Priorizar el email del barbero y actualizar el usuario
                $barbero->user->update(['email' => $barbero->email]);
                echo "Sincronizado email para usuario {$barbero->user->id}: {$barbero->email}\n";
            }
        }
    }

    /**
     * Verificar integridad de datos después de la migración
     */
    private function verifyDataIntegrity(): void
    {
        // Verificar que todos los barberos tengan user_id
        $barberosWithoutUsers = Barbero::whereNull('user_id')->count();
        if ($barberosWithoutUsers > 0) {
            throw new Exception("Falló la migración: {$barberosWithoutUsers} barberos sin user_id");
        }

        // Verificar que todos los usuarios de barberos tengan el rol correcto
        $barberos = Barbero::whereNotNull('user_id')->with('user')->get();
        foreach ($barberos as $barbero) {
            if ($barbero->user && ! $barbero->user->hasRole('barbero')) {
                throw new Exception("Usuario {$barbero->user->id} no tiene rol barbero");
            }
        }

        // Verificar que los emails estén sincronizados
        foreach ($barberos as $barbero) {
            if ($barbero->user && $barbero->email !== $barbero->user->email) {
                throw new Exception("Emails no sincronizados para barbero {$barbero->id}");
            }
        }

        echo "✓ Verificación de integridad completada exitosamente\n";
        echo '✓ Total barberos migrados: '.$barberos->count()."\n";
        echo "✓ Todos los barberos tienen usuarios vinculados\n";
        echo "✓ Todos los usuarios tienen rol 'barbero'\n";
        echo "✓ Emails sincronizados entre tablas\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir la migración eliminando las vinculaciones creadas
        DB::transaction(function () {
            // Remover rol barbero de usuarios que fueron creados en esta migración
            $barberos = Barbero::whereNotNull('user_id')->with('user')->get();

            foreach ($barberos as $barbero) {
                if ($barbero->user && $barbero->user->hasRole('barbero')) {
                    $barbero->user->removeRole('barbero');
                }
            }

            // Nota: No eliminamos los usuarios creados para preservar integridad referencial
            // Solo removemos las vinculaciones y roles
            echo "Roles de barbero removidos. Los usuarios creados se mantienen para preservar integridad.\n";
        });
    }
};
