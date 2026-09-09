<?php

namespace Database\Seeders;

use App\Models\Barbero;
use Illuminate\Database\Seeder;

class BarberoSeeder extends Seeder
{
    /**
     * Ejecuta las semillas de la base de datos.
     *
     * @return void
     */
    public function run()
    {
        Barbero::create([
            'nombre_completo' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'password' => 'password123',
            'telefono' => '123456789',
            'especialidad' => 'Corte de cabello',
            'experiencia' => '10 años de experiencia en corte de cabello y estilizado.',
            'foto' => null,
        ]);

        Barbero::create([
            'nombre_completo' => 'María García',
            'email' => 'maria.garcia@example.com',
            'password' => 'password123',
            'telefono' => '987654321',
            'especialidad' => 'Barba y bigote',
            'experiencia' => '5 años de experiencia en estilizado de barba.',
            'foto' => null,
        ]);

        // Agrega más barberos aquí
    }
}
