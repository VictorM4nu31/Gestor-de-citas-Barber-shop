<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    /**
     * Ejecuta las semillas de la base de datos.
     *
     * @return void
     */
    public function run()
    {
        Servicio::create([
            'nombre' => 'Corte de Cabello',
            'descripcion' => 'Corte de cabello con estilo a tu elección.',
            'duracion' => 45,
            'precio' => 15.00,
            'foto' => 'ruta/a/la/foto2.jpg',
        ]);

        // Agrega más servicios aquí
    }
}

