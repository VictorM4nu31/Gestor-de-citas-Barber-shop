<?php

namespace App\Http\Controllers;

use App\Models\Barbero;
use App\Models\Servicio;
use App\Models\Cita;
use App\Models\User;
use App\Models\GalleryImage;
use App\Http\Requests\StoreBarberoRequest;
use App\Http\Requests\UpdateBarberoRequest;
use App\Services\BarberoValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $galleryImages = GalleryImage::active()->get();
        $citasHoy = Cita::whereDate('fecha', today())->count();

        return view('admin.dashboard', compact('barberos', 'servicios', 'galleryImages', 'citasHoy'));
    }

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
            DB::transaction(function () use ($validated, $request) {
                $user = \App\Models\User::create([
                    'name' => $validated['nombre_completo'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ]);

                $user->assignRole('barbero');

                $barberoData = [
                    'nombre_completo' => $validated['nombre_completo'],
                    'email' => $validated['email'],
                    'telefono' => $validated['telefono'],
                    'especialidad' => $validated['especialidad'],
                    'experiencia' => $validated['experiencia'],
                    'user_id' => $user->id,
                    'activo' => true,
                ];

                if ($request->hasFile('foto')) {
                    $barberoData['foto'] = $request->file('foto')->store('barberos', 'public');
                }

                $barbero = Barbero::create($barberoData);

                if ($request->has('servicios')) {
                    $barbero->servicios()->sync($request->input('servicios', []));
                }

                // Log successful creation for debugging/tracing
                Log::info('Barbero creado', [
                    'barbero_id' => $barbero->id ?? null,
                    'email' => $barbero->email ?? ($validated['email'] ?? null),
                ]);
            });

            return redirect()->route('admin.barberos.index')->with('success', __('messages.barber.created'));
        } catch (\Exception $e) {
            // Log the exception for inspection
            Log::error('Error al crear barbero', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => __('messages.barber.create_error', ['error' => $e->getMessage()])]);
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
                if ($request->hasFile('foto')) {
                    if ($barbero->foto) {
                        Storage::disk('public')->delete($barbero->foto);
                    }
                    $validated['foto'] = $request->file('foto')->store('barberos', 'public');
                }

                if (!$barbero->user_id || !$barbero->user) {
                    $user = User::create([
                        'name' => $validated['nombre_completo'],
                        'email' => $validated['email'],
                        'password' => Hash::make($validated['password'] ?? 'temporal123'),
                    ]);

                    $user->assignRole('barbero');

                    $barbero->user_id = $user->id;
                } else {
                    $user = $barbero->user;
                    $userUpdateData = [
                        'name' => $validated['nombre_completo'],
                        'email' => $validated['email'],
                    ];

                    if ($request->filled('password')) {
                        $userUpdateData['password'] = Hash::make($validated['password']);
                    }

                    $user->update($userUpdateData);
                }

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

                if ($request->has('servicios')) {
                    $barbero->servicios()->sync($request->input('servicios', []));
                } else {
                    $barbero->servicios()->sync([]);
                }
            });

            return redirect()->route('admin.barberos.index')->with('success', __('messages.barber.updated'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => __('messages.barber.update_error', ['error' => $e->getMessage()])]);
        }
    }

    public function barberosDarDeBaja(Barbero $barbero)
    {
        try {
            $this->barberoValidationService->validateCanDeactivate($barbero);

            DB::transaction(function () use ($barbero) {
                $barbero->update([
                    'activo' => false,
                    'fecha_baja' => now(),
                ]);

                if ($barbero->user) {
                    $barbero->user->removeRole('barbero');
                }
            });

            return redirect()->route('admin.barberos.index')
                ->with('success', __('messages.barber.deactivated'));
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => __('messages.barber.deactivate_error', ['error' => $e->getMessage()])]);
        }
    }

    public function barberosReactivar(Barbero $barbero)
    {
        try {
            $this->barberoValidationService->validateCanReactivate($barbero);

            DB::transaction(function () use ($barbero) {
                $barbero->update([
                    'activo' => true,
                    'fecha_baja' => null,
                ]);

                if ($barbero->user) {
                    $barbero->user->assignRole('barbero');
                } else {
                    $user = User::create([
                        'name' => $barbero->nombre_completo,
                        'email' => $barbero->email,
                        'password' => Hash::make('temporal123'),
                    ]);

                    $user->assignRole('barbero');

                    $barbero->update(['user_id' => $user->id]);
                }
            });

            return redirect()->route('admin.barberos.index')
                ->with('success', __('messages.barber.reactivated'));
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => __('messages.barber.reactivate_error', ['error' => $e->getMessage()])]);
        }
    }

    public function barberosEliminarPermanente(Barbero $barbero)
    {
        try {
            $this->barberoValidationService->validateCanDelete($barbero);

            DB::transaction(function () use ($barbero) {
                if ($barbero->foto) {
                    Storage::disk('public')->delete($barbero->foto);
                }

                if ($barbero->user) {
                    $barbero->user->roles()->detach();
                    $barbero->user->delete();
                } else {
                    $barbero->delete();
                }
            });

            return redirect()->route('admin.barberos.index')
                ->with('success', __('messages.barber.permanently_deleted'));
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

        return redirect()->route('admin.barberos.index')->with('success', __('messages.barber.deleted'));
    }

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

        return redirect()->route('admin.servicios.index')->with('success', __('messages.service.created'));
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

        return redirect()->route('admin.servicios.index')->with('success', __('messages.service.updated'));
    }

    public function serviciosDestroy(Servicio $servicio)
    {
        if ($servicio->foto) {
            Storage::disk('public')->delete($servicio->foto);
        }
        $servicio->delete();

        return redirect()->route('admin.servicios.index')->with('success', __('messages.service.deleted'));
    }

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

        return redirect()->route('admin.citas.index')->with('success', __('messages.appointment.created'));
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

        return redirect()->route('admin.citas.index')->with('success', __('messages.appointment.updated'));
    }

    public function citasDestroy(Cita $cita)
    {
        $cita->delete();
        return redirect()->route('admin.citas.index')->with('success', __('messages.appointment.deleted'));
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
