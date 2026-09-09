<?php

namespace Database\Seeders;

use App\Models\Barbero;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TestCitaSeeder extends Seeder
{
    public function run()
    {
        // Buscar o crear usuario con tu email
        $user = User::where('email', 'angelesvictor690@gmail.com')->first();
        if (! $user) {
            echo "Creando usuario Victor Angeles...\n";
            $user = User::create([
                'name' => 'Victor Manuel Angeles Muñiz',
                'email' => 'angelesvictor690@gmail.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);

            // Asignar rol de usuario si existe
            if (class_exists(Role::class)) {
                $userRole = Role::where('name', 'user')->first();
                if ($userRole) {
                    $user->assignRole('user');
                }
            }
        }

        // Buscar barbero Marco específicamente
        $barbero = Barbero::where('email', 'marco@gmail.com')->where('activo', true)->first();
        if (! $barbero) {
            echo "❌ No se encontró el barbero Marco (marco@gmail.com) o no está activo.\n";
            // Intentar buscar cualquier barbero activo
            $barbero = Barbero::where('activo', true)->first();
            if (! $barbero) {
                echo "❌ No hay barberos activos en el sistema.\n";

                return;
            }
        }

        // Obtener servicio publicado
        $servicio = Servicio::where('publicado', true)->first();
        if (! $servicio) {
            echo "❌ No se encontró un servicio publicado.\n";

            return;
        }

        echo "Usuario: {$user->name} ({$user->email})\n";
        echo "Barbero: {$barbero->nombre_completo} ({$barbero->email})\n";
        echo "Servicio: {$servicio->nombre} - \${$servicio->precio}\n";

        // Verificar si ya existe una cita similar para hoy o mañana
        $citaExistente = Cita::where('id_usuario', $user->id)
            ->where('fecha', '>=', Carbon::today()->format('Y-m-d'))
            ->first();

        if ($citaExistente) {
            echo "⚠️ Ya existe una cita para este usuario. Eliminando la anterior...\n";
            echo "Cita anterior: {$citaExistente->fecha} a las {$citaExistente->hora}\n";
            $citaExistente->delete();
        }

        // Crear la cita para mañana
        $fechaCita = Carbon::tomorrow()->format('Y-m-d');
        $horaCita = '14:00'; // 2:00 PM

        // Verificar que el barbero no tenga cita a esa hora
        $conflicto = Cita::where('id_barbero', $barbero->id)
            ->where('fecha', $fechaCita)
            ->where('hora', $horaCita)
            ->first();

        if ($conflicto) {
            echo "⚠️ El barbero ya tiene una cita a las {$horaCita}. Cambiando a las 15:00...\n";
            $horaCita = '15:00';
        }

        // Crear la cita
        $cita = Cita::create([
            'nombre_completo' => $user->name,
            'numero_telefono' => '+52 123 456 7890',
            'correo_electronico' => $user->email,
            'fecha' => $fechaCita,
            'hora' => $horaCita,
            'id_barbero' => $barbero->id,
            'id_usuario' => $user->id,
            'servicios' => $servicio->id,
            'costo' => $servicio->precio,
            'estado' => 'pendiente',
        ]);
        $cita->serviciosMany()->sync([$servicio->id]);

        echo "\n✅ Cita creada exitosamente!\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📅 ID de la cita: {$cita->id}\n";
        echo "📅 Fecha: {$cita->fecha}\n";
        echo "🕐 Hora: {$cita->hora}\n";
        echo "💰 Costo: \${$cita->costo}\n";
        echo "📊 Estado: {$cita->estado}\n";
        echo "👨‍💼 Barbero: {$barbero->nombre_completo}\n";
        echo "✂️ Servicio: {$servicio->nombre}\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "\n🔑 Credenciales para iniciar sesión:\n";
        echo "📧 Email: {$user->email}\n";
        echo "🔒 Password: password\n";
        echo "\n🌐 Rutas para probar:\n";
        echo "• Login: /login\n";
        echo "• Ver citas: /citas\n";
        echo "• Dashboard: /dashboard\n";
    }
}
