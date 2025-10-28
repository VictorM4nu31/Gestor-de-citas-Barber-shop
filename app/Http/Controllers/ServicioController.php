<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::all();
<<<<<<< HEAD
        return view('admin.dashboard', compact('servicios'));
=======
        return view('servicios.index', compact('servicios'));
>>>>>>> prog_front
    }

    public function create()
    {
<<<<<<< HEAD
        return view('admin.servicios.services-create');
=======
        return view('servicios.create');
>>>>>>> prog_front
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'duracion' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ],[
            'foto.max' => 'El tamaño máximo permitido de la imagen es de 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('servicios', 'public');
        }

        Servicio::create($validated);

<<<<<<< HEAD
        return redirect()->route('admin.dashboard')->with('success', 'Servicio creado exitosamente.');
=======
        return redirect()->route('servicios.index')->with('success', 'Servicio creado exitosamente.');
>>>>>>> prog_front
    }

    public function show(Servicio $servicio)
    {
        return view('servicios.show', compact('servicio'));
    }

    public function edit(Servicio $servicio)
    {
<<<<<<< HEAD
        return view('admin.servicios.services-edit', compact('servicio'));
=======
        return view('servicios.edit', compact('servicio'));
>>>>>>> prog_front
    }

    public function update(Request $request, Servicio $servicio)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'duracion' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ],[
            'foto.max' => 'El tamaño máximo permitido de la imagen es de 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            if ($servicio->foto) {
                Storage::disk('public')->delete($servicio->foto);
            }
            $validated['foto'] = $request->file('foto')->store('servicios', 'public');
        }

        $servicio->update($validated);

<<<<<<< HEAD
        return redirect()->route('admin.dashboard')->with('success', 'Servicio actualizado exitosamente.');
=======
        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado exitosamente.');
>>>>>>> prog_front
    }

    public function destroy(Servicio $servicio)
    {
        if ($servicio->foto) {
            Storage::disk('public')->delete($servicio->foto);
        }
        $servicio->delete();

<<<<<<< HEAD
        return redirect()->route('admin.dashboard')->with('success', 'Servicio eliminado exitosamente.');
=======
        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado exitosamente.');
>>>>>>> prog_front
    }
}

