<?php

namespace App\Http\Controllers;

use App\Models\Barbero;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Servicio;
use App\Http\Requests\Barbero\StoreRequest;
use App\Http\Requests\Barbero\UpdateRequest;


class BarberoController extends Controller
{
    public function index()
    {
        $barberos = Barbero::all();
        $servicios = Servicio::all();
        return view('admin.dashboard', compact('barberos', 'servicios'));
    }

    public function create()
    {
        return view('admin.barberos.table-users-create');
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('barberos', 'public');
        }

        $barbero = Barbero::create($validated);

        // Asignar el rol de barbero
        $barbero->assignRole('barbero');

        return redirect()->route('admin.table-users')->with('success', 'Barbero creado exitosamente.');
    }

    public function show(Barbero $barbero)
    {
        return view('barberos.show', compact('barbero'));
    }

    public function edit(Barbero $barbero)
    {
        return view('admin.barberos.table-users-edit', compact('barbero'));
    }

    public function update(UpdateRequest $request, Barbero $barbero)
    {
        $validated = $request->validated();

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('foto')) {
            if ($barbero->foto) {
                Storage::disk('public')->delete($barbero->foto);
            }
            $validated['foto'] = $request->file('foto')->store('barberos', 'public');
        }

        $barbero->update($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Barbero actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $barbero = Barbero::findOrFail($id);

        // Eliminar todas las citas asociadas al barbero
        Cita::where('id_barbero', $id)->delete();

        // Ahora eliminar al barbero
        $barbero->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Barbero eliminado exitosamente.');
    }
}