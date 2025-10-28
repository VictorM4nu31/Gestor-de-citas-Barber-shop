<?php

namespace App\Http\Controllers;

use App\Models\Barbero;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
<<<<<<< HEAD
use App\Models\Servicio;
use App\Http\Requests\Barbero\StoreRequest;
use App\Http\Requests\Barbero\UpdateRequest;
=======
>>>>>>> prog_front


class BarberoController extends Controller
{
<<<<<<< HEAD
    public function index()
    {
        $barberos = Barbero::all();
        $servicios = Servicio::all();
        return view('admin.dashboard', compact('barberos', 'servicios'));
=======
    public function welcome()
    {
        $barberos = Barbero::all();
        return view('welcome', compact('barberos'));
    }

    public function index()
    {
        $barberos = Barbero::all();
        return view('barberos.index', compact('barberos'));
>>>>>>> prog_front
    }

    public function create()
    {
<<<<<<< HEAD
        return view('admin.barberos.table-users-create');
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
=======
        return view('barberos.create');
    }

    public function store(Request $request)
    {
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
>>>>>>> prog_front

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('barberos', 'public');
        }

<<<<<<< HEAD
        $barbero = Barbero::create($validated);

        // Asignar el rol de barbero
        $barbero->assignRole('barbero');

        return redirect()->route('admin.table-users')->with('success', 'Barbero creado exitosamente.');
=======
        Barbero::create($validated);

        return redirect()->route('barberos.index')->with('success', 'Barbero creado exitosamente.');
>>>>>>> prog_front
    }

    public function show(Barbero $barbero)
    {
        return view('barberos.show', compact('barbero'));
    }

    public function edit(Barbero $barbero)
    {
<<<<<<< HEAD
        return view('admin.barberos.table-users-edit', compact('barbero'));
    }

    public function update(UpdateRequest $request, Barbero $barbero)
    {
        $validated = $request->validated();

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
=======
        return view('barberos.edit', compact('barbero'));
    }

    public function update(Request $request, Barbero $barbero)
    {
        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'email' => 'required|email|unique:barberos,email,' . $barbero->id,
            'password' => 'nullable|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'required|string|max:100',
            'experiencia' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ],[
            'foto.max' => 'El tamaño máximo permitido de la imagen es de 2MB.',
        ]);

        $data = $request->all();
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
>>>>>>> prog_front
        }

        if ($request->hasFile('foto')) {
            if ($barbero->foto) {
                Storage::disk('public')->delete($barbero->foto);
            }
            $validated['foto'] = $request->file('foto')->store('barberos', 'public');
        }

        $barbero->update($validated);

<<<<<<< HEAD
        return redirect()->route('admin.dashboard')->with('success', 'Barbero actualizado exitosamente.');
=======
        return redirect()->route('barberos.index')->with('success', 'Barbero actualizado exitosamente.');
>>>>>>> prog_front
    }

    public function destroy($id)
    {
        $barbero = Barbero::findOrFail($id);

        // Eliminar todas las citas asociadas al barbero
        Cita::where('id_barbero', $id)->delete();

        // Ahora eliminar al barbero
        $barbero->delete();

<<<<<<< HEAD
        return redirect()->route('admin.dashboard')->with('success', 'Barbero eliminado exitosamente.');
    }
}
=======
        return redirect()->route('barberos.index')->with('success', 'Barbero eliminado exitosamente.');
    }
}
>>>>>>> prog_front
