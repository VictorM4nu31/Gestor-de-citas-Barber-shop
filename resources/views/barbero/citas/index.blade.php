<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold">Todas mis Citas</h1>
    </x-slot>

    <div id="view" class="h-full w-screen flex flex-row">
        <div class="bg-surface flex-grow text-secondary p-6">
            <!-- Mensajes de éxito/error -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Navegación -->
            <div class="mb-6">
                <a href="{{ route('barbero.dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Volver al Dashboard
                </a>
            </div>

            <!-- Gestión de Citas para Barbero -->
            <div class="bg-white p-4 sm:p-6 md:p-8 rounded-lg shadow-lg w-full">
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
                                            <span class="px-2 py-1 rounded-full text-xs
                                                @if($cita->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                                @elseif($cita->estado === 'atendida') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $cita->estado_texto }}
                                            </span>
                                            @if($cita->fecha_atencion)
                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ $cita->fecha_atencion->format('d/m/Y H:i') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('barbero.citas.show', $cita) }}" 
                                               class="text-indigo-600 hover:text-indigo-900">
                                                Ver
                                            </a>
                                            @if($cita->puedeSerAtendida())
                                                <form action="{{ route('barbero.citas.atender', $cita) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="text-green-600 hover:text-green-900" 
                                                            onclick="return confirm('¿Confirmas que has atendido a este cliente?')">
                                                        Marcar Atendida
                                                    </button>
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
            </div>
        </div>
    </div>
</x-app-layout>