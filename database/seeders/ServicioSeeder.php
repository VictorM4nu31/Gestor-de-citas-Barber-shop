<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    /**
     * Ejecuta las semillas de la base de datos.
     *
     * @return void
     */
    public function run()
    {
        $servicios = [
            [
                'nombre' => 'Corte de Cabello',
                'descripcion' => 'Corte de cabello con estilo a tu elección.',
                'duracion' => 45,
                'precio' => 15.00,
                'publicado' => true,
                'orden' => 1,
            ],
            [
                'nombre' => 'Arreglo de Barba',
                'descripcion' => 'Perfilado y arreglo profesional de barba.',
                'duracion' => 30,
                'precio' => 10.00,
                'publicado' => true,
                'orden' => 2,
            ],
            [
                'nombre' => 'Corte + Barba',
                'descripcion' => 'Servicio completo: corte de cabello y arreglo de barba.',
                'duracion' => 60,
                'precio' => 22.00,
                'publicado' => true,
                'orden' => 3,
            ],
        ];

        foreach ($servicios as $datos) {
            Servicio::updateOrCreate(
                ['nombre' => $datos['nombre']],
                $datos
            );
        }
    }
}
