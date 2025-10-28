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
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('servicios.create');
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

        return redirect()->route('servicios.index')->with('success', 'Servicio creado exitosamente.');
    }

    public function show(Servicio $servicio)
    {
        return view('servicios.show', compact('servicio'));
    }

    public function edit(Servicio $servicio)
    {
        return view('servicios.edit', compact('servicio'));
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

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado exitosamente.');
    }

    public function destroy(Servicio $servicio)
    {
        if ($servicio->foto) {
            Storage::disk('public')->delete($servicio->foto);
        }
        $servicio->delete();

        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado exitosamente.');
    }
}

