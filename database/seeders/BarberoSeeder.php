<?php

namespace Database\Seeders;

use App\Models\Barbero;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BarberoSeeder extends Seeder
{
    /**
     * Ejecuta las semillas de la base de datos.
     *
     * @return void
     */
    public function run()
    {
        $barberos = [
            [
                'nombre_completo' => 'Juan Pérez',
                'email' => 'juan.perez@example.com',
                'telefono' => '123456789',
                'especialidad' => 'Corte de cabello',
                'experiencia' => '10 años de experiencia en corte de cabello y estilizado.',
            ],
            [
                'nombre_completo' => 'María García',
                'email' => 'maria.garcia@example.com',
                'telefono' => '987654321',
                'especialidad' => 'Barba y bigote',
                'experiencia' => '5 años de experiencia en estilizado de barba.',
            ],
            [
                'nombre_completo' => 'Carlos Ramírez',
                'email' => 'carlos.ramirez@example.com',
                'telefono' => '555123456',
                'especialidad' => 'Fades y cortes modernos',
                'experiencia' => '8 años creando fades, texturas y estilos contemporáneos.',
            ],
            [
                'nombre_completo' => 'Sofía Martínez',
                'email' => 'sofia.martinez@example.com',
                'telefono' => '555654321',
                'especialidad' => 'Color y styling',
                'experiencia' => '7 años de experiencia en color, styling y asesoría de imagen.',
            ],
            [
                'nombre_completo' => 'Luis Hernández',
                'email' => 'luis.hernandez@example.com',
                'telefono' => '555789012',
                'especialidad' => 'Afeitado clásico',
                'experiencia' => '12 años de experiencia en barbería tradicional y navaja.',
            ],
        ];

        $serviciosIds = Servicio::publicadosOrdenados()->pluck('id')->all();

        foreach ($barberos as $datos) {
            $user = User::firstOrCreate(
                ['email' => $datos['email']],
                [
                    'name' => $datos['nombre_completo'],
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole('barbero');

            $barbero = Barbero::updateOrCreate(
                ['email' => $datos['email']],
                array_merge($datos, [
                    'user_id' => $user->id,
                    'activo' => true,
                    'fecha_baja' => null,
                ])
            );

            if (! empty($serviciosIds)) {
                $barbero->servicios()->syncWithoutDetaching($serviciosIds);
            }
        }
    }
}
