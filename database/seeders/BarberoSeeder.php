<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barbero;

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
            'telefono' => '123456789',
            'especialidad' => 'Corte de cabello',
            'experiencia' => '10 años de experiencia en corte de cabello y estilizado.',
            'foto' => 'ruta/a/la/foto1.jpg',
        ]);

        // Agrega más barberos aquí
    }
}
