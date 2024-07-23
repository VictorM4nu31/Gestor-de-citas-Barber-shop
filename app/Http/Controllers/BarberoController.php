<?php

namespace App\Http\Controllers;

use App\Models\Barbero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class BarberoController extends Controller
{
    public function index()
    {
        $barberos = Barbero::all();
        return view('admin.dashboard', compact('barberos'));
    }

    public function create()
    {
        return view('admin.table-users-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'email' => 'required|email|unique:barberos,email',
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'required|string|max:100',
            'experiencia' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('barberos', 'public');
        }

        Barbero::create($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Barbero creado exitosamente.');
    }

    public function show(Barbero $barbero)
    {
        return view('barberos.show', compact('barbero'));
    }

    public function edit(Barbero $barbero)
    {
        return view('admin.table-users-edit', compact('barbero'));
    }

    public function update(Request $request, Barbero $barbero)
    {
        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'email' => 'required|email|unique:barberos,email,' . $barbero->id,
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'required|string|max:100',
            'experiencia' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($barbero->foto) {
                Storage::disk('public')->delete($barbero->foto);
            }
            $validated['foto'] = $request->file('foto')->store('barberos', 'public');
        }

        $barbero->update($validated);

        return redirect()->route('admin.table-users')->with('success', 'Barbero actualizado exitosamente.');
    }

    public function destroy(Barbero $barbero)
    {
        if ($barbero->foto) {
            Storage::disk('public')->delete($barbero->foto);
        }
        $barbero->delete();

        return redirect()->route('admin.table-users')->with('success', 'Barbero eliminado exitosamente.');
    }
}
