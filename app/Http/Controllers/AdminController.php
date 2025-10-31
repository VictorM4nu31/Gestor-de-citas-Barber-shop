<?php

namespace App\Http\Controllers;

use App\Models\Barbero;
use App\Models\Servicio;
use App\Models\Cita;
use App\Models\User;
use App\Http\Requests\StoreBarberoRequest;
use App\Http\Requests\UpdateBarberoRequest;
use App\Services\BarberoValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    protected $barberoValidationService;

    public function __construct(BarberoValidationService $barberoValidationService)
    {
        $this->barberoValidationService = $barberoValidationService;
    }
    public function dashboard()
    {
        $barberos = \App\Models\Barbero::all();
        $servicios = \App\Models\Servicio::all();
        return view('admin.dashboard', compact('barberos', 'servicios'));
    }

    // ============ BARBEROS CRUD ============
    public function barberosIndex(Request $request)
    {
        $query = Barbero::query();
        
        // Filtrar por estado si se especifica
        if ($request->has('estado')) {
            switch ($request->get('estado')) {
                case 'activos':
                    $query->activos();
                    break;
                case 'inactivos':
                    $query->inactivos();
                    break;
                // 'todos' o cualquier otro valor muestra todos
            }
        }
        
        $barberos = $query->get();
        return view('admin.barberos.index', compact('barberos'));
    }

    public function barberosCreate()
    {
        $servicios = Servicio::publicadosOrdenados()->get();
        return view('admin.barberos.create', compact('servicios'));
    }

    public function barberosStore(StoreBarberoRequest $request)
    {
        $validated = $request->validated();

        try {
            \DB::transaction(function () use ($validated, $request) {
                // 1. Crear usuario en tabla users
                $user = \App\Models\User::create([
                    'name' => $validated['nombre_completo'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ]);

                // 2. Asignar rol "barbero" al usuario creado
                $user->assignRole('barbero');

                // 3. Preparar datos del barbero
                $barberoData = [
                    'nombre_completo' => $validated['nombre_completo'],
                    'email' => $validated['email'],
                    'telefono' => $validated['telefono'],
                    'especialidad' => $validated['especialidad'],
                    'experiencia' => $validated['experiencia'],
                    'user_id' => $user->id,
                    'activo' => true,
                ];

                // 4. Manejar foto si existe
                if ($request->hasFile('foto')) {
                    $barberoData['foto'] = $request->file('foto')->store('barberos', 'public');
                }

                // 5. Crear barbero vinculado al usuario
                $barbero = Barbero::create($barberoData);
                
                // 6. Sincronizar servicios seleccionados
                if ($request->has('servicios')) {
                    $barbero->servicios()->sync($request->input('servicios', []));
                }
            });

            return redirect()->route('admin.barberos.index')->with('success', 'Barbero creado exitosamente.');
        } catch (\Exception $e) {
            // Manejar errores y rollback automático por la transacción
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear el barbero: ' . $e->getMessage()]);
        }
    }

    public function barberosShow(Barbero $barbero)
    {
        return view('admin.barberos.show', compact('barbero'));
    }

    public function barberosEdit(Barbero $barbero)
    {
        $servicios = Servicio::publicadosOrdenados()->get();
        $serviciosAsignados = $barbero->servicios->pluck('id')->toArray();
        return view('admin.barberos.edit', compact('barbero', 'servicios', 'serviciosAsignados'));
    }

    public function barberosUpdate(UpdateBarberoRequest $request, Barbero $barbero)
    {
        $validated = $request->validated();
        
        // Validar integridad de datos si el barbero está activo
        if ($barbero->activo) {
            $this->barberoValidationService->validateIsActive($barbero);
        }

        try {
            DB::transaction(function () use ($validated, $request, $barbero) {
                // 1. Manejar foto si existe
                if ($request->hasFile('foto')) {
                    if ($barbero->foto) {
                        Storage::disk('public')->delete($barbero->foto);
                    }
                    $validated['foto'] = $request->file('foto')->store('barberos', 'public');
                }

                // 2. Crear usuario automáticamente si no existe
                if (!$barbero->user_id || !$barbero->user) {
                    $user = User::create([
                        'name' => $validated['nombre_completo'],
                        'email' => $validated['email'],
                        'password' => Hash::make($validated['password'] ?? 'temporal123'),
                    ]);
                    
                    // Asignar rol "barbero" al usuario creado
                    $user->assignRole('barbero');
                    
                    $barbero->user_id = $user->id;
                } else {
                    // 3. Sincronizar cambios con el usuario existente
                    $user = $barbero->user;
                    $userUpdateData = [
                        'name' => $validated['nombre_completo'],
                        'email' => $validated['email'],
                    ];

                    // 4. Actualizar contraseña en tabla users si se proporciona
                    if ($request->filled('password')) {
                        $userUpdateData['password'] = Hash::make($validated['password']);
                    }

                    $user->update($userUpdateData);
                }

                // 5. Actualizar datos del barbero (sin password ya que se maneja en users)
                $barberoData = [
                    'nombre_completo' => $validated['nombre_completo'],
                    'email' => $validated['email'],
                    'telefono' => $validated['telefono'],
                    'especialidad' => $validated['especialidad'],
                    'experiencia' => $validated['experiencia'],
                    'user_id' => $barbero->user_id,
                ];

                if (isset($validated['foto'])) {
                    $barberoData['foto'] = $validated['foto'];
                }

                $barbero->update($barberoData);
                
                // 6. Sincronizar servicios seleccionados
                if ($request->has('servicios')) {
                    $barbero->servicios()->sync($request->input('servicios', []));
                } else {
                    // Si no se envían servicios, desasignar todos
                    $barbero->servicios()->sync([]);
                }
            });

            return redirect()->route('admin.barberos.index')->with('success', 'Barbero actualizado exitosamente.');
        } catch (\Exception $e) {
            // Manejar errores y rollback automático por la transacción
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error al actualizar el barbero: ' . $e->getMessage()]);
        }
    }

    public function barberosDarDeBaja(Barbero $barbero)
    {
        try {
            // Validar que el barbero puede ser dado de baja
            $this->barberoValidationService->validateCanDeactivate($barbero);
            
            DB::transaction(function () use ($barbero) {
                // 1. Marcar barbero como inactivo (activo = false)
                $barbero->update([
                    'activo' => false,
                    'fecha_baja' => now(),
                ]);

                // 2. Desactivar usuario correspondiente si existe
                if ($barbero->user) {
                    // Remover rol de barbero para desactivar acceso
                    $barbero->user->removeRole('barbero');
                    
                    // Opcional: También podríamos desactivar completamente el usuario
                    // pero mantenemos el usuario para preservar integridad referencial
                }
            });

            return redirect()->route('admin.barberos.index')
                ->with('success', 'Barbero dado de baja exitosamente.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al dar de baja el barbero: ' . $e->getMessage()]);
        }
    }

    public function barberosReactivar(Barbero $barbero)
    {
        try {
            // Validar que el barbero puede ser reactivado
            $this->barberoValidationService->validateCanReactivate($barbero);
            
            DB::transaction(function () use ($barbero) {
                // 1. Marcar barbero como activo (activo = true)
                $barbero->update([
                    'activo' => true,
                    'fecha_baja' => null,
                ]);

                // 2. Reactivar usuario correspondiente
                if ($barbero->user) {
                    // Asignar rol de barbero para reactivar acceso
                    $barbero->user->assignRole('barbero');
                } else {
                    // Si no existe usuario, crear uno automáticamente
                    $user = User::create([
                        'name' => $barbero->nombre_completo,
                        'email' => $barbero->email,
                        'password' => Hash::make('temporal123'), // Contraseña temporal
                    ]);
                    
                    // Asignar rol "barbero" al usuario creado
                    $user->assignRole('barbero');
                    
                    // Vincular barbero con el usuario
                    $barbero->update(['user_id' => $user->id]);
                }
            });

            return redirect()->route('admin.barberos.index')
                ->with('success', 'Barbero reactivado exitosamente.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al reactivar el barbero: ' . $e->getMessage()]);
        }
    }

    public function barberosEliminarPermanente(Barbero $barbero)
    {
        try {
            // Validar que el barbero puede ser eliminado permanentemente
            $this->barberoValidationService->validateCanDelete($barbero);
            
            DB::transaction(function () use ($barbero) {

                // 1. Eliminar foto si existe
                if ($barbero->foto) {
                    Storage::disk('public')->delete($barbero->foto);
                }

                // 2. Eliminar usuario de tabla users (cascade eliminará barbero)
                if ($barbero->user) {
                    // Remover roles antes de eliminar
                    $barbero->user->roles()->detach();
                    $barbero->user->delete();
                } else {
                    // Si no hay usuario asociado, eliminar barbero directamente
                    $barbero->delete();
                }
            });

            return redirect()->route('admin.barberos.index')
                ->with('success', 'Barbero eliminado permanentemente del sistema.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
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
