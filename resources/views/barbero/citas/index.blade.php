<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-white">Todas mis Citas</h1>
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
            <div class="mb-6">
                <x-ui.button type="secondary" href="{{ route('barbero.dashboard') }}">
                    ← Volver al Dashboard
                </x-ui.button>
            </div>

            <!-- Gestión de Citas para Barbero -->
            <x-ui.card padding="lg" class="w-full">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl sm:text-3xl font-bold text-black">Todas mis Citas</h1>
                    <div class="text-sm text-gray-600">
                        Total: {{ $citas->total() }}
                    </div>
                </div>

                @if($citas->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicios</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($citas as $cita)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $cita->hora }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div>
                                                <div class="font-medium">{{ $cita->nombre_completo }}</div>
                                                <div class="text-gray-500">{{ $cita->numero_telefono }}</div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $cita->servicios_nombres_texto }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                            <x-ui.badge 
                                                type="@if($cita->estado === 'pendiente') warning @elseif($cita->estado === 'atendida') success @else danger @endif">
                                                {{ $cita->estado_texto }}
                                            </x-ui.badge>
                                            @if($cita->fecha_atencion)
                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ $cita->fecha_atencion->format('d/m/Y H:i') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium space-x-2">
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
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-6">
                        {{ $citas->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay citas asignadas</h3>
                        <p class="mt-1 text-sm text-gray-500">No tienes todavía citas programadas.</p>
                    </div>
                @endif
            </x-ui.card>
        </div>
    </div>
</x-app-layout>