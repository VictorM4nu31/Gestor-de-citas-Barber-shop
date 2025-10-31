<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Mis Citas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-secondary">
                    @if($citas->count() > 0)
                        <div class="grid gap-4">
                            @foreach($citas as $cita)
                                <div class="border rounded-lg p-4 bg-surface">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-semibold text-lg">{{ $cita->nombre_completo }}</h3>
                                            <p class="text-muted">{{ $cita->fecha }} - {{ $cita->hora }}</p>
                                            <p class="text-muted">Barbero: {{ $cita->barbero->nombre_completo ?? 'No asignado' }}</p>
                                            <p class="text-muted">Costo: ${{ number_format($cita->costo, 2) }}</p>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('citas.show', $cita->id) }}" 
                                               class="bg-info hover:bg-info/90 text-white font-bold py-2 px-4 rounded">
                                                Ver Detalles
                                            </a>
                                            <form method="POST" action="{{ route('citas.destroy', $cita->id) }}" 
                                                  onsubmit="return confirm('¿Estás seguro de que quieres cancelar esta cita?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-danger hover:bg-danger/90 text-white font-bold py-2 px-4 rounded">
                                                    Cancelar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-muted mb-4">No tienes citas programadas.</p>
                            <a href="{{ route('citas.create') }}" 
                               class="bg-info hover:bg-info/90 text-white font-bold py-2 px-4 rounded">
                                Agendar Nueva Cita
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>