<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Servicio;
use App\Models\Barbero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\Cita\StoreRequest;

class CitaController extends Controller
{
    public function create()
    {
        $servicios = Servicio::publicadosOrdenados()->get();
        $barberos = Barbero::activos()->get();
        return view('usuario.citas.create', compact('servicios', 'barberos'));
    }

    public function store(StoreRequest $request)
    {
        $user = Auth::user();
        $datos = $request->validated();
        $fecha = $datos['fecha'];
        $hora = $datos['hora'];
        $barberoId = $datos['id_barbero'];

        if (Cita::usuarioTieneMaximasFuturas($user->id)) {
            return redirect()->back()->with('error', 'Ya existen 2 citas pendientes, no puedes agendar una tercera cita.');
        }
        if (Cita::barberoNoDisponible($barberoId, $fecha, $hora)) {
            return redirect()->back()->with('error', 'Sin disponibilidad, asegurate de haber elegido alguno de los horarios disponibles');
        }

        $cita = new Cita();
        $cita->nombre_completo = $datos['nombre_completo'];
        $cita->numero_telefono = $datos['numero_telefono'];
        $cita->correo_electronico = $datos['correo_electronico'];
        $cita->fecha = $fecha;
        $cita->hora = $hora;
        $cita->id_barbero = $barberoId;
        $cita->id_usuario = $user->id;

        $servicios = $datos['servicios'];
        $cita->servicios = implode(',', $servicios);
        $cita->costo = \App\Models\Servicio::whereIn('id', $servicios)->sum('precio');

        $cita->save();

        return redirect()->route('citas.index')->with('success', 'Cita agendada exitosamente.');
    }

    public function index()
    {
        $user = Auth::user();
        Cita::where('id_usuario', $user->id)
            ->where('fecha', '<', Carbon::today())
            ->delete();

        $citas = Cita::where('id_usuario', $user->id)
            ->where('fecha', '>=', Carbon::today())
            ->get();

        return view('usuario.citas.index', compact('citas'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $cita = Cita::where('id', $id)
            ->where('id_usuario', $user->id)
            ->firstOrFail();

        return view('usuario.citas.show', compact('cita'));
    }

    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();

        return redirect()->route('citas.index')->with('success', 'Cita cancelada exitosamente.');
    } 
    
    public function checkAvailability(Request $request)
    {
        $barberoId = $request->input('barbero_id');
        $fecha = $request->input('fecha');

        $citas = Cita::where('id_barbero', $barberoId)
            ->where('fecha', $fecha)
            ->get(['hora', 'nombre_completo', 'servicios']);

        return response()->json($citas);
    }

    public function getServiciosByBarbero($barberoId)
    {
        $barbero = Barbero::where('id', $barberoId)
            ->where('activo', true)
            ->first();

        if (!$barbero) {
            return response()->json(['error' => 'Barbero no encontrado o inactivo'], 404);
        }

        $servicios = $barbero->serviciosPublicados()->get();

        return response()->json($servicios);
    }

}
