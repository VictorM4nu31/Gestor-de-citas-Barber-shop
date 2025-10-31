<?php

namespace App\Http\Controllers;

use App\Models\Barbero;
use App\Models\Servicio;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $barberos = \App\Models\Barbero::all();
        $servicios = \App\Models\Servicio::all();
        return view('admin.dashboard', compact('barberos', 'servicios'));
    }

    // ============ BARBEROS CRUD ============
    public function barberosIndex()
    {
        $barberos = Barbero::all();
        return view('admin.barberos.index', compact('barberos'));
    }

    public function barberosCreate()
    {
        return view('admin.barberos.create');
    }

    public function barberosStore(Request $request)
    {
        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'email' => 'required|email|unique:barberos,email',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'required|string|max:255',
            'experiencia' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('barberos', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);
        Barbero::create($validated);

        return redirect()->route('admin.barberos.index')->with('success', 'Barbero creado exitosamente.');
    }

    public function barberosShow(Barbero $barbero)
    {
        return view('admin.barberos.show', compact('barbero'));
    }

    public function barberosEdit(Barbero $barbero)
    {
        return view('admin.barberos.edit', compact('barbero'));
    }

    public function barberosUpdate(Request $request, Barbero $barbero)
    {
        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'email' => 'required|email|unique:barberos,email,' . $barbero->id,
            'password' => 'nullable|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'required|string|max:255',
            'experiencia' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($barbero->foto) {
                Storage::disk('public')->delete($barbero->foto);
            }
            $validated['foto'] = $request->file('foto')->store('barberos', 'public');
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $barbero->update($validated);

        return redirect()->route('admin.barberos.index')->with('success', 'Barbero actualizado exitosamente.');
    }

    public function barberosDestroy(Barbero $barbero)
    {
        if ($barbero->foto) {
            Storage::disk('public')->delete($barbero->foto);
        }
        $barbero->delete();

        return redirect()->route('admin.barberos.index')->with('success', 'Barbero eliminado exitosamente.');
    }

    // ============ SERVICIOS CRUD ============
    public function serviciosIndex()
    {
        $servicios = Servicio::all();
        return view('admin.servicios.index', compact('servicios'));
    }

    public function serviciosCreate()
    {
        return view('admin.servicios.create');
    }

    public function serviciosStore(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'duracion' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'publicado' => 'boolean',
            'orden' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('servicios', 'public');
        }

        $validated['publicado'] = $request->has('publicado');
        Servicio::create($validated);

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio creado exitosamente.');
    }

    public function serviciosShow(Servicio $servicio)
    {
        return view('admin.servicios.show', compact('servicio'));
    }

    public function serviciosEdit(Servicio $servicio)
    {
        return view('admin.servicios.edit', compact('servicio'));
    }

    public function serviciosUpdate(Request $request, Servicio $servicio)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'duracion' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'publicado' => 'boolean',
            'orden' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('foto')) {
            if ($servicio->foto) {
                Storage::disk('public')->delete($servicio->foto);
            }
            $validated['foto'] = $request->file('foto')->store('servicios', 'public');
        }

        $validated['publicado'] = $request->has('publicado');
        $servicio->update($validated);

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio actualizado exitosamente.');
    }

    public function serviciosDestroy(Servicio $servicio)
    {
        if ($servicio->foto) {
            Storage::disk('public')->delete($servicio->foto);
        }
        $servicio->delete();

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio eliminado exitosamente.');
    }

    // ============ CITAS CRUD ============
    public function citasIndex()
    {
        $citas = Cita::with(['barbero', 'servicios'])->get();
        return view('admin.citas.index', compact('citas'));
    }

    public function citasCreate()
    {
        $servicios = Servicio::all();
        $barberos = Barbero::all();
        return view('admin.citas.create', compact('servicios', 'barberos'));
    }

    public function citasStore(Request $request)
    {
        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'numero_telefono' => 'required|string|max:20',
            'correo_electronico' => 'required|email|max:255',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:servicios,id',
            'id_barbero' => 'required|exists:barberos,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|string',
            'total_servicios' => 'required|numeric|min:0',
        ]);

        $cita = new Cita();
        $cita->nombre_completo = $validated['nombre_completo'];
        $cita->numero_telefono = $validated['numero_telefono'];
        $cita->correo_electronico = $validated['correo_electronico'];
        $cita->id_barbero = $validated['id_barbero'];
        $cita->fecha = $validated['fecha'];
        $cita->hora = $validated['hora'];
        $cita->costo = $validated['total_servicios'];
        $cita->save();

        $cita->servicios()->attach($validated['servicios']);

        return redirect()->route('admin.citas.index')->with('success', 'Cita agendada exitosamente.');
    }

    public function citasShow(Cita $cita)
    {
        $cita->load(['barbero', 'servicios']);
        return view('admin.citas.show', compact('cita'));
    }

    public function citasEdit(Cita $cita)
    {
        $servicios = Servicio::all();
        $barberos = Barbero::all();
        $cita->load(['barbero', 'servicios']);
        return view('admin.citas.edit', compact('cita', 'servicios', 'barberos'));
    }

    public function citasUpdate(Request $request, Cita $cita)
    {
        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'numero_telefono' => 'required|string|max:20',
            'correo_electronico' => 'required|email|max:255',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:servicios,id',
            'id_barbero' => 'required|exists:barberos,id',
            'fecha' => 'required|date',
            'hora' => 'required|string',
            'total_servicios' => 'required|numeric|min:0',
        ]);

        $cita->update([
            'nombre_completo' => $validated['nombre_completo'],
            'numero_telefono' => $validated['numero_telefono'],
            'correo_electronico' => $validated['correo_electronico'],
            'id_barbero' => $validated['id_barbero'],
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'costo' => $validated['total_servicios'],
        ]);

        $cita->servicios()->sync($validated['servicios']);

        return redirect()->route('admin.citas.index')->with('success', 'Cita actualizada exitosamente.');
    }

    public function citasDestroy(Cita $cita)
    {
        $cita->delete();
        return redirect()->route('admin.citas.index')->with('success', 'Cita eliminada exitosamente.');
    }

    public function citasCheckAvailability(Request $request)
    {
        $barberoId = $request->input('barbero_id');
        $fecha = $request->input('fecha');

        $citas = Cita::where('id_barbero', $barberoId)
            ->where('fecha', $fecha)
            ->select('hora')
            ->get();

        return response()->json($citas);
    }
}
