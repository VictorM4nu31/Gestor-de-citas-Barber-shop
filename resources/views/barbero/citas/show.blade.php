<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-white">Detalles de la Cita</h1>
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

            <!-- Navegación -->
            <div class="mb-6 space-x-2">
                <x-ui.button type="secondary" href="{{ route('barbero.dashboard') }}">
                    ← Dashboard
                </x-ui.button>
                <x-ui.button type="info" href="{{ route('barbero.citas.index') }}">
                    Todas las Citas
                </x-ui.button>
            </div>

            <!-- Detalles de la Cita -->
            <x-ui.card class="overflow-hidden">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Información de la Cita
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Detalles completos de la cita programada.
                    </p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Estado
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <x-ui.badge 
                                    type="@if($cita->estado === 'pendiente') warning @elseif($cita->estado === 'atendida') success @else danger @endif">
                                    {{ $cita->estado_texto }}
                                </x-ui.badge>
                                @if($cita->fecha_atencion)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Atendida el {{ $cita->fecha_atencion->format('d/m/Y a las H:i') }}
                                    </div>
                                @endif
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Fecha y Hora
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ \Carbon\Carbon::parse($cita->fecha)->format('l, d \d\e F \d\e Y') }} a las {{ $cita->hora }}
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Cliente
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $cita->nombre_completo }}
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Teléfono
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <a href="tel:{{ $cita->numero_telefono }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $cita->numero_telefono }}
                                </a>
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Correo Electrónico
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <a href="mailto:{{ $cita->correo_electronico }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $cita->correo_electronico }}
                                </a>
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Servicios Solicitados
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                    @foreach($cita->servicios_names as $servicio)
                                        <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                            <div class="w-0 flex-1 flex items-center">
                                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="ml-2 flex-1 w-0 truncate">
                                                    {{ $servicio }}
                                                </span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Costo Total
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                ${{ number_format($cita->costo, 2) }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </x-ui.card>

            <!-- Acciones -->
            @if($cita->puedeSerAtendida())
                <div class="mt-6">
                    <form action="{{ route('barbero.citas.atender', $cita) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <x-ui.button type="success" 
                                onclick="return confirm('{{ __('dashboard.barber.confirm_attended') }}')">
                            ✓ {{ __('dashboard.barber.mark_as_attended') }}
                        </x-ui.button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>