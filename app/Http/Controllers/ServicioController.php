<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Servicio\StoreRequest;
use App\Http\Requests\Servicio\UpdateRequest;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = \App\Models\Servicio::all();
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        if (!\Illuminate\Support\Facades\Auth::check() || !\Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        return view('servicios.create');
    }

    public function store(StoreRequest $request)
    {
        if (!\Illuminate\Support\Facades\Auth::check() || !\Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        $validated = $request->validated();
        if ($request->hasFile('foto')) {
            // Asegurar que la carpeta exista en el disco public
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('servicios');
            $validated['foto'] = $request->file('foto')->store('servicios', 'public');
        }

        Servicio::create($validated);

        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.servicios.index')->with('success', 'Servicio creado exitosamente.');
        }

        return redirect()->route('servicios.index')->with('success', 'Servicio creado exitosamente.');
    }

    public function show(Servicio $servicio)
    {
        return view('servicios.show', compact('servicio'));
    }

    public function edit(Servicio $servicio)
    {
        if (!\Illuminate\Support\Facades\Auth::check() || !\Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        return view('servicios.edit', compact('servicio'));
    }

    public function update(UpdateRequest $request, Servicio $servicio)
    {
        if (!\Illuminate\Support\Facades\Auth::check() || !\Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        $validated = $request->validated();
        if ($request->hasFile('foto')) {
            if ($servicio->foto) {
                Storage::disk('public')->delete($servicio->foto);
            }
            // Asegurar que la carpeta exista en el disco public
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('servicios');
            $validated['foto'] = $request->file('foto')->store('servicios', 'public');
        }

        $servicio->update($validated);

        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.servicios.index')->with('success', 'Servicio actualizado exitosamente.');
        }

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado exitosamente.');
    }

    public function destroy(Servicio $servicio)
    {
        if (!\Illuminate\Support\Facades\Auth::check() || !\Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        if ($servicio->foto) {
            Storage::disk('public')->delete($servicio->foto);
        }
        $servicio->delete();

        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.servicios.index')->with('success', 'Servicio eliminado exitosamente.');
        }

        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado exitosamente.');
    }
}

