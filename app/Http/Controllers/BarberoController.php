<?php

namespace App\Http\Controllers;

use App\Models\Barbero;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Servicio;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Http\Requests\Barbero\StoreRequest;
use App\Http\Requests\Barbero\UpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class BarberoController extends Controller
{
    // Nota: los checks de rol se realizan en cada método para evitar dependencia en
    // la clase base Controller que en este proyecto no define middleware().
    public function welcome()
    {
        $barberos = Barbero::all();

        // Si el usuario autenticado es admin, mostrar todos los servicios (preview),
        // en caso contrario sólo los publicados y ordenados.
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            $servicios = Servicio::orderBy('orden', 'asc')->orderBy('created_at', 'desc')->get();
        } else {
            $servicios = Servicio::publicadosOrdenados()->get();
        }

        // Añadir descripción truncada desde el controlador para mantener la vista limpia
        $servicios = $servicios->map(function ($s) {
            $s->descripcion_corta = Str::limit($s->descripcion, 120);
            return $s;
        });

        return view('welcome', compact('barberos', 'servicios'));
    }

    public function dashboard()
    {
        // El middleware ya verifica auth, role:barbero y active.barbero
        // por lo que no necesitamos verificaciones adicionales aquí
        $user = Auth::user();
        $barbero = $user->barbero;
        
        if (!$barbero) {
            // Esto no debería pasar si el middleware funciona correctamente
            abort(403, 'No se encontró el perfil de barbero asociado.');
        }
        
        // Obtener citas del barbero usando la relación correcta
        $citas = $barbero->citas()
            ->with('usuario')
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc')
            ->get();
            
        return view('barbero.dashboard', compact('citas', 'barbero'));
    }

    // Public methods for displaying barberos (no admin functionality)
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
