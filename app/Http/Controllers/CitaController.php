<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Barbero;
use App\Models\Servicio;

class CitaController extends Controller
{
    public function create(Request $request)
    {
        $fecha = $request->query('date'); // Obtener la fecha seleccionada del calendario
        $barberos = Barbero::all();
        $servicios = Servicio::all();
        return view('citas.create', compact('fecha', 'barberos', 'servicios'));
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'numero_telefono' => 'required|string|max:20',
            'correo_electronico' => 'required|email|max:255',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i|after:09:00|before:21:00',
            'id_servicio' => 'required|exists:servicios,id',
            'id_barbero' => 'required|exists:barberos,id',
        ]);

        // Guardar la cita en la base de datos
        Cita::create([
            'nombre_completo' => $request->nombre_completo,
            'numero_telefono' => $request->numero_telefono,
            'correo_electronico' => $request->correo_electronico,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'id_servicio' => $request->id_servicio,
            'id_barbero' => $request->id_barbero,
        ]);

        return redirect()->route('citas.create')->with('success', 'Cita agendada exitosamente.');
    }
}
