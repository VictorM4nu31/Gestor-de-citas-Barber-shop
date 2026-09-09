<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-white">{{ __('dashboard.barber.control_panel') }} - {{ $barbero->nombre_completo }}</h1>
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
                    {{ __('dashboard.barber.view_all_appointments') }}
                </x-ui.button>
            </div>

            <!-- Citas de Hoy -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">{{ __('dashboard.barber.todays_appointments') }} ({{ now()->format('d/m/Y') }})</h2>
                
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
                                                    <x-ui.status-badge :estado="$cita->estado" />
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
                                                        {{ __('dashboard.barber.services_label') }} {{ $cita->servicios_nombres_texto }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0 flex space-x-2">
                                            <x-ui.button type="info" size="sm" href="{{ route('barbero.citas.show', $cita) }}">
                                                {{ __('dashboard.barber.view_details') }}
                                            </x-ui.button>
                                            @if($cita->puedeSerAtendida())
                                                <form action="{{ route('barbero.citas.atender', $cita) }}" method="POST" class="inline" x-data x-on:confirmed-attend-today-{{ $cita->id }}.window="$el.submit()">
                                                    @csrf
                                                    @method('PATCH')
                                                    <x-ui.button type="success" size="sm" @click="$dispatch('open-modal-attend-today-{{ $cita->id }}', { trigger: $el })">
                                                        {{ __('dashboard.barber.mark_attended') }}
                                                    </x-ui.button>
                                                </form>
                                                <x-ui.confirm-modal id="attend-today-{{ $cita->id }}" title="Marcar cita como atendida" message="¿Confirmas que la cita de {{ $cita->nombre_completo }} ya fue atendida?" variant="neutral" />
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </x-ui.card>
                @else
                    <p class="text-muted">{{ __('dashboard.barber.no_appointments_today') }}</p>
                @endif
            </div>

            <!-- Próximas Citas -->
            <div>
                <h2 class="text-xl font-semibold mb-4">{{ __('dashboard.barber.upcoming_appointments') }}</h2>
                
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
                                                        {{ __('dashboard.barber.services_label') }} {{ $cita->servicios_nombres_texto }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <x-ui.button type="info" size="sm" href="{{ route('barbero.citas.show', $cita) }}">
                                                {{ __('dashboard.barber.view_details') }}
                                            </x-ui.button>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </x-ui.card>
                @else
                    <p class="text-muted">{{ __('dashboard.barber.no_upcoming_appointments') }}</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
