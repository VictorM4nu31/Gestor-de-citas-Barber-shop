<?php

namespace App\Http\Controllers;

use App\Models\Barbero;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\GalleryImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class BarberoController extends Controller
{
    public function welcome()
    {
        $barberos = Barbero::all();

        if (Auth::check() && Auth::user()->hasRole('admin')) {
            $servicios = Servicio::orderBy('orden', 'asc')->orderBy('created_at', 'desc')->get();
        } else {
            $servicios = Servicio::publicadosOrdenados()->get();
        }

        $servicios = $servicios->map(function ($s) {
            $s->descripcion_corta = Str::limit($s->descripcion, 120);
            return $s;
        });

        // Load gallery images for welcome page
        $galleryImages = GalleryImage::active()->ordered()->get();

        return view('welcome', compact('barberos', 'servicios', 'galleryImages'));
    }

    public function dashboard()
    {
        $user = Auth::user();
        $barbero = $user->barbero;
        
        if (!$barbero) {
            abort(403, 'No se encontró el perfil de barbero asociado.');
        }
        
        $citasHoy = $barbero->citas()
            ->with('usuario')
            ->where('fecha', now()->toDateString())
            ->orderBy('hora', 'asc')
            ->get();

        $citasFuturas = $barbero->citas()
            ->with('usuario')
            ->where('fecha', '>', now()->toDateString())
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc')
            ->take(10)
            ->get();
            
        return view('barbero.dashboard', compact('citasHoy', 'citasFuturas', 'barbero'));
    }

    public function citas()
    {
        $user = Auth::user();
        $barbero = $user->barbero;
        
        if (!$barbero) {
            abort(403, 'No se encontró el perfil de barbero asociado.');
        }
        
        $citas = $barbero->citas()
            ->with('usuario')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->paginate(20);
            
        return view('barbero.citas.index', compact('citas', 'barbero'));
    }

    public function marcarAtendida(Cita $cita)
    {
        $user = Auth::user();
        $barbero = $user->barbero;
        
        if ($cita->id_barbero !== $barbero->id) {
            abort(403, 'No tienes permiso para modificar esta cita.');
        }
        
        if (!$cita->puedeSerAtendida()) {
            return redirect()->back()->with('error', __('messages.appointment.cannot_attend'));
        }
        
        $cita->marcarComoAtendida();
        
        return redirect()->back()->with('success', __('messages.appointment.attended'));
    }

    public function verCita(Cita $cita)
    {
        $user = Auth::user();
        $barbero = $user->barbero;
        
        if ($cita->id_barbero !== $barbero->id) {
            abort(403, 'No tienes permiso para ver esta cita.');
        }
        
        return view('barbero.citas.show', compact('cita', 'barbero'));
    }

    public function index()
    {
        $barberos = Barbero::all();
        return view('usuario.publico.barberos-index', compact('barberos'));
    }

    public function show(Barbero $barbero)
    {
        return view('usuario.publico.barberos-show', compact('barbero'));
    }
}
