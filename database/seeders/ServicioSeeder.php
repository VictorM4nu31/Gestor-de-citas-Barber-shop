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
                'descripcion' => 'Corte de cabello con asesoría de estilo y acabado profesional.',
                'duracion' => 45,
                'precio' => 280.00,
                'publicado' => true,
                'orden' => 1,
            ],
            [
                'nombre' => 'Corte Clásico',
                'descripcion' => 'Corte tradicional con tijera, máquina y acabado natural.',
                'duracion' => 35,
                'precio' => 240.00,
                'publicado' => true,
                'orden' => 2,
            ],
            [
                'nombre' => 'Arreglo de Barba',
                'descripcion' => 'Perfilado, recorte y arreglo profesional de barba.',
                'duracion' => 30,
                'precio' => 180.00,
                'publicado' => true,
                'orden' => 3,
            ],
            [
                'nombre' => 'Corte + Barba',
                'descripcion' => 'Servicio completo de corte, perfilado y arreglo de barba.',
                'duracion' => 75,
                'precio' => 420.00,
                'publicado' => true,
                'orden' => 4,
            ],
            [
                'nombre' => 'Afeitado Clásico',
                'descripcion' => 'Afeitado tradicional con toalla caliente y navaja.',
                'duracion' => 30,
                'precio' => 220.00,
                'publicado' => true,
                'orden' => 5,
            ],
            [
                'nombre' => 'Lavado y Styling',
                'descripcion' => 'Lavado relajante y peinado con productos profesionales.',
                'duracion' => 20,
                'precio' => 120.00,
                'publicado' => true,
                'orden' => 6,
            ],
            [
                'nombre' => 'Diseño de Barba',
                'descripcion' => 'Diseño personalizado de barba según tus facciones.',
                'duracion' => 45,
                'precio' => 260.00,
                'publicado' => true,
                'orden' => 7,
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
