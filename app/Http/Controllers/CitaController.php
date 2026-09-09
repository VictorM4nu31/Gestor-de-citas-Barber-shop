<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cita\AvailabilityRequest;
use App\Http\Requests\Cita\StoreRequest;
use App\Models\Barbero;
use App\Models\Cita;
use App\Models\Servicio;
use App\Notifications\AppointmentCreatedNotification;
use App\Services\BarberoAvailabilityService;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CitaController extends Controller
{
    public function __construct(private readonly BarberoAvailabilityService $availabilityService) {}

    public function create()
    {
        $servicios = Servicio::publicadosOrdenados()->get();
        $barberos = Barbero::activos()->get();
        $repeatCita = Cita::where('id_usuario', Auth::id())
            ->with('serviciosMany')
            ->find(request()->query('repeat'));
        $rebookServiceIds = $repeatCita?->serviciosMany->modelKeys() ?? [];
        $rebookBarberoId = $repeatCita?->id_barbero;

        return view('usuario.citas.create', compact('servicios', 'barberos', 'rebookServiceIds', 'rebookBarberoId'));
    }

    public function repeat(Cita $cita): RedirectResponse
    {
        abort_unless($cita->id_usuario === Auth::id(), 404);

        return redirect()->route('citas.create', ['repeat' => $cita->id]);
    }

    public function dashboard()
    {
        $proximaCita = Cita::where('id_usuario', Auth::id())
            ->whereDate('fecha', '>=', today())
            ->where('estado', '!=', 'cancelada')
            ->with(['barbero', 'serviciosMany'])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->first();

        $totalCitas = Cita::where('id_usuario', Auth::id())
            ->whereDate('fecha', '>=', today())
            ->where('estado', '!=', 'cancelada')
            ->count();

        return view('dashboard', compact('proximaCita', 'totalCitas'));
    }

    public function store(StoreRequest $request)
    {
        try {
            $user = Auth::user();
            $datos = $request->validated();
            $fecha = $datos['fecha'];
            $hora = $datos['hora'];
            $barberoId = $datos['id_barbero'];
            $barbero = Barbero::activos()->find($barberoId);
            $servicios = Servicio::publicadosOrdenados()
                ->whereIn('id', $datos['servicios'])
                ->get();

            // Verificar límite de citas
            if (Cita::usuarioTieneMaximasFuturas($user->id)) {
                return redirect()->back()->with('error', __('messages.appointment.max_appointments'));
            }

            if (! $barbero || $servicios->count() !== count(array_unique($datos['servicios']))) {
                return redirect()->back()->with('error', __('messages.appointment.no_availability'));
            }

            $available = $this->availabilityService
                ->slotsFor($barbero, CarbonImmutable::createFromFormat('Y-m-d', $fecha, config('app.timezone')), $servicios)
                ->contains(fn (array $slot): bool => $slot['value'] === $hora);

            if (! $available) {
                return redirect()->back()->with('error', __('messages.appointment.no_availability'));
            }

            // Crear la cita
            $cita = new Cita;
            $cita->nombre_completo = $datos['nombre_completo'];
            $cita->numero_telefono = $datos['numero_telefono'];
            $cita->correo_electronico = $datos['correo_electronico'];
            $cita->fecha = $fecha;
            $cita->hora = $hora;
            $cita->id_barbero = $barberoId;
            $cita->id_usuario = $user->id;
            $cita->estado = 'pendiente'; // Agregar estado por defecto

            $serviceIds = $datos['servicios'];
            $cita->servicios = implode(',', $serviceIds);
            $cita->costo = $servicios->sum('precio');

            $cita->save();
            $cita->serviciosMany()->sync($serviceIds);

            try {
                $user->notify(new AppointmentCreatedNotification($cita->load(['barbero', 'serviciosMany'])));
            } catch (\Throwable $notificationException) {
                Log::warning('No se pudo enviar la confirmación de cita.', [
                    'cita_id' => $cita->id,
                    'error' => $notificationException->getMessage(),
                ]);
            }

            return redirect()->route('citas.index')->with('success', __('messages.appointment.created'));

        } catch (\Exception $e) {
            \Log::error('Error al crear cita: '.$e->getMessage());

            return redirect()->back()->with('error', 'Error al crear la cita: '.$e->getMessage());
        }
    }

    public function index()
    {
        $user = Auth::user();
        Cita::where('id_usuario', $user->id)
            ->where('fecha', '<', Carbon::today())
            ->delete();

        $citas = Cita::where('id_usuario', $user->id)
            ->where('fecha', '>=', Carbon::today())
            ->get();

        return view('usuario.citas.index', compact('citas'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $cita = Cita::where('id', $id)
            ->where('id_usuario', $user->id)
            ->firstOrFail();

        return view('usuario.citas.show', compact('cita'));
    }

    public function destroy($id)
    {
        $cita = Cita::whereKey($id)
            ->where('id_usuario', Auth::id())
            ->firstOrFail();
        $cita->delete();

        return redirect()->route('citas.index')->with('success', __('messages.appointment.cancelled'));
    }

    public function checkAvailability(Request $request)
    {
        $barberoId = $request->input('barbero_id');
        $fecha = $request->input('fecha');

        $horas = Cita::where('id_barbero', $barberoId)
            ->where('fecha', $fecha)
            ->orderBy('hora')
            ->pluck('hora');

        return response()->json($horas);
    }

    public function availableSlots(AvailabilityRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $barbero = Barbero::activos()->findOrFail($validated['barbero_id']);
        $servicios = Servicio::publicadosOrdenados()
            ->whereIn('id', $validated['servicios'])
            ->get();

        if ($servicios->count() !== count(array_unique($validated['servicios']))) {
            throw ValidationException::withMessages([
                'servicios' => 'Uno o más servicios no están disponibles.',
            ]);
        }

        $assignedServiceIds = $barbero->serviciosPublicados()
            ->whereIn('servicios.id', $servicios->modelKeys())
            ->pluck('servicios.id');

        if ($assignedServiceIds->count() !== $servicios->count()) {
            throw ValidationException::withMessages([
                'servicios' => 'Uno o más servicios no están disponibles para este barbero.',
            ]);
        }

        $date = CarbonImmutable::createFromFormat('Y-m-d', $validated['fecha'], config('app.timezone'));

        return response()->json([
            'date' => $date->toDateString(),
            'duration' => (int) $servicios->sum('duracion'),
            'slots' => $this->availabilityService->slotsFor($barbero, $date, $servicios)->values(),
        ]);
    }

    public function getServiciosByBarbero($barberoId)
    {
        $barbero = Barbero::where('id', $barberoId)
            ->where('activo', true)
            ->first();

        if (! $barbero) {
            return response()->json(['error' => __('messages.barber.not_found')], 404);
        }

        $servicios = $barbero->serviciosPublicados()->get();

        return response()->json($servicios);
    }
}
