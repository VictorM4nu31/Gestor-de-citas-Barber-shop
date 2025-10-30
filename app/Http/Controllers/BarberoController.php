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
        if (!Auth::check() || !Auth::user()->hasRole('barbero')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        $user = Auth::user();
        $barbero = Barbero::where('email', $user->email)->first();
        if (!$barbero) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        // Mejor uso de relaciones y filtrado
        $citas = $barbero->citas()
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

    /**
     * @param \App\Http\Requests\Barbero\StoreRequest|\Illuminate\Http\Request $request
     */
    public function store(\App\Http\Requests\Barbero\StoreRequest $request)
    {
        if (!\Illuminate\Support\Facades\Auth::check() || !\Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        $validated = $request->validated();
        if ($request->hasFile('foto')) {
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('barberos');
            $validated['foto'] = $request->file('foto')->store('barberos', 'public');
        }
        $password = $request->input('password');
        if ($password) {
            $user = \App\Models\User::create([
                'name' => $validated['nombre_completo'],
                'email' => $validated['email'],
                'password' => $password, // hashed via User model
            ]);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'barbero']);
            try {
                $user->assignRole('barbero');
            } catch (\Exception $e) {}
            $validated['user_id'] = $user->id;
        }
        $barbero = \App\Models\Barbero::create($validated);
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

    /**
     * @param \App\Http\Requests\Barbero\UpdateRequest|\Illuminate\Http\Request $request
     * @param \App\Models\Barbero $barbero
     */
    public function update(\App\Http\Requests\Barbero\UpdateRequest $request, \App\Models\Barbero $barbero)
    {
        if (!\Illuminate\Support\Facades\Auth::check() || !\Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        $validated = $request->validated();
        if ($request->hasFile('foto')) {
            if ($barbero->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($barbero->foto);
            }
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('barberos');
            $validated['foto'] = $request->file('foto')->store('barberos', 'public');
        }
        $barbero->update($validated);
        if ($barbero->user) {
            $userUpdates = [
                'name' => $validated['nombre_completo'] ?? $barbero->nombre_completo,
                'email' => $validated['email'] ?? $barbero->email,
            ];
            $passwordUpdate = $request->input('password');
            if (!empty($passwordUpdate)) {
                $userUpdates['password'] = $passwordUpdate;
            }
            try {
                $barbero->user->update($userUpdates);
            } catch (\Exception $e) {}
        }
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

        // Eliminar la foto asociada si existe (evitar archivos huérfanos)
        if ($barbero->foto) {
            Storage::disk('public')->delete($barbero->foto);
        }

        // Eliminar usuario asociado si existe (mantener sincronía entre tablas)
        if ($barbero->user_id) {
            try {
                $barbero->user()->delete();
            } catch (\Exception $e) {
                // registrar si hace falta
            }
        }

        // Ahora eliminar al barbero
        $barbero->delete();

        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.barberos.index')->with('success', 'Barbero eliminado exitosamente.');
        }

        return redirect()->route('barberos.index')->with('success', 'Barbero eliminado exitosamente.');
    }
}
