<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-white">Panel de Control - {{ $barbero->nombre_completo }}</h1>
    </x-slot>

    <div id="view" class="h-full w-screen flex flex-row">
        <div class="bg-surface flex-grow text-secondary p-6">
            <!-- Mensajes de éxito/error -->
            @if(session('success'))
                <x-ui.alert type="success" class="mb-4">
                    {{ session('success') }}
                </x-ui.alert>
            @endif

            @if(session('error'))
                <x-ui.alert type="danger" class="mb-4">
                    {{ session('error') }}
                </x-ui.alert>
            @endif

            <!-- Navegación rápida -->
            <div class="mb-6">
                <x-ui.button type="info" href="{{ route('barbero.citas.index') }}">
                    Ver Todas las Citas
                </x-ui.button>
            </div>

            <!-- Citas de Hoy -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Citas de Hoy ({{ now()->format('d/m/Y') }})</h2>
                
                @if($citasHoy->count() > 0)
                    <x-ui.card class="overflow-hidden">
                        <ul class="divide-y divide-accent">
                            @foreach($citasHoy as $cita)
                                <li class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-primary truncate">
                                                    {{ $cita->nombre_completo }}
                                                </p>
                                                <div class="ml-2 flex-shrink-0 flex">
                                                    <x-ui.badge 
                                                        type="@if($cita->estado === 'pendiente') warning @elseif($cita->estado === 'atendida') success @else danger @endif">
                                                        {{ $cita->estado_texto }}
                                                    </x-ui.badge>
                                                </div>
                                            </div>
                                            <div class="mt-2 sm:flex sm:justify-between">
                                                <div class="sm:flex">
                                                    <p class="flex items-center text-sm text-muted">
                                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-muted" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $cita->hora }}
                                                    </p>
                                                    <p class="mt-2 flex items-center text-sm text-muted sm:mt-0 sm:ml-6">
                                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-muted" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                                        </svg>
                                                        {{ $cita->correo_electronico }}
                                                    </p>
                                                </div>
                                                <div class="mt-2 flex items-center text-sm text-muted sm:mt-0">
                                                    <p class="text-sm text-secondary">
                                                        Servicios: {{ $cita->servicios_nombres_texto }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0 flex space-x-2">
                                            <x-ui.button type="info" size="sm" href="{{ route('barbero.citas.show', $cita) }}">
                                                Ver
                                            </x-ui.button>
                                            @if($cita->puedeSerAtendida())
                                                <form action="{{ route('barbero.citas.atender', $cita) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <x-ui.button type="success" size="sm" 
                                                            onclick="return confirm('¿Confirmas que has atendido a este cliente?')">
                                                        Marcar Atendida
                                                    </x-ui.button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </x-ui.card>
                @else
                    <p class="text-muted">No tienes citas programadas para hoy.</p>
                @endif
            </div>

            <!-- Próximas Citas -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Próximas Citas</h2>
                
                @if($citasFuturas->count() > 0)
                    <x-ui.card class="overflow-hidden">
                        <ul class="divide-y divide-accent">
                            @foreach($citasFuturas as $cita)
                                <li class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-primary truncate">
                                                    {{ $cita->nombre_completo }}
                                                </p>
                                                <div class="ml-2 flex-shrink-0 flex">
                                                    <x-ui.badge type="info">
                                                        {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                                                    </x-ui.badge>
                                                </div>
                                            </div>
                                            <div class="mt-2 sm:flex sm:justify-between">
                                                <div class="sm:flex">
                                                    <p class="flex items-center text-sm text-muted">
                                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-muted" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $cita->hora }}
                                                    </p>
                                                </div>
                                                <div class="mt-2 flex items-center text-sm text-muted sm:mt-0">
                                                    <p class="text-sm text-secondary">
                                                        Servicios: {{ $cita->servicios_nombres_texto }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <x-ui.button type="info" size="sm" href="{{ route('barbero.citas.show', $cita) }}">
                                                Ver Detalles
                                            </x-ui.button>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </x-ui.card>
                @else
                    <p class="text-muted">No tienes citas futuras programadas.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>