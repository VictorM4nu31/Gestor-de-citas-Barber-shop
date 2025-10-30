<?php

namespace App\Http\Controllers;

use App\Models\Barbero;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Servicio;
use App\Http\Requests\Barbero\StoreRequest;
use App\Http\Requests\Barbero\UpdateRequest;
use Illuminate\Support\Facades\Auth;


class BarberoController extends Controller
{
    // Nota: los checks de rol se realizan en cada método para evitar dependencia en
    // la clase base Controller que en este proyecto no define middleware().
    public function welcome()
    {
        $barberos = Barbero::all();
        return view('welcome', compact('barberos'));
    }

    public function dashboard()
    {
        // Verificar rol barbero
        if (!Auth::check() || !Auth::user()->hasRole('barbero')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        // Obtener las citas asignadas al barbero autenticado
        $user = Auth::user();
        
        // Buscar el barbero por email del usuario
        $barbero = Barbero::where('email', $user->email)->first();
        
        if (!$barbero) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        $citas = Cita::where('id_barbero', $barbero->id)
            ->with('usuario')
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc')
            ->get();

        return view('worker.dashboard', compact('citas', 'barbero'));
    }

    public function index()
    {
        $barberos = Barbero::all();
        return view('barberos.index', compact('barberos'));
    }

    public function create()
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        return view('barberos.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'email' => 'required|email|unique:barberos,email',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'required|string|max:100',
            'experiencia' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ],[
            'foto.max' => 'El tamaño máximo permitido de la imagen es de 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('barberos', 'public');
        }

        Barbero::create($validated);

        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.barberos.index')->with('success', 'Barbero creado exitosamente.');
        }

        return redirect()->route('barberos.index')->with('success', 'Barbero creado exitosamente.');
    }

    public function show(Barbero $barbero)
    {
        return view('barberos.show', compact('barbero'));
    }

    public function edit(Barbero $barbero)
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        return view('barberos.edit', compact('barbero'));
    }

    public function update(Request $request, Barbero $barbero)
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        $validated = $request->validate([
            
            'email' => 'required|email|unique:barberos,email,' . $barbero->id,
            'password' => 'nullable|string|min:8',
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'required|string|max:100',
            'experiencia' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ],[
            'foto.max' => 'El tamaño máximo permitido de la imagen es de 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            if ($barbero->foto) {
                Storage::disk('public')->delete($barbero->foto);
            }
            $validated['foto'] = $request->file('foto')->store('barberos', 'public');
        }

        // Remover password del array si está vacío
        if (!$request->filled('password')) {
            unset($validated['password']);
        }

        $barbero->update($validated);

        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.barberos.index')->with('success', 'Barbero actualizado exitosamente.');
        }

        return redirect()->route('barberos.index')->with('success', 'Barbero actualizado exitosamente.');
    }

    public function destroy($id)
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        $barbero = Barbero::findOrFail($id);

        // Eliminar todas las citas asociadas al barbero
        Cita::where('id_barbero', $id)->delete();

        // Ahora eliminar al barbero
        $barbero->delete();

        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.barberos.index')->with('success', 'Barbero eliminado exitosamente.');
        }

        return redirect()->route('barberos.index')->with('success', 'Barbero eliminado exitosamente.');
    }
}
