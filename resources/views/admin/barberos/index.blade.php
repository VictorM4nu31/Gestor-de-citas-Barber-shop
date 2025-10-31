<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Lista de Barberos</h2>
            <a href="{{ route('admin.barberos.create') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded flex items-center space-x-2">
                <i class="fas fa-user-plus"></i>
                <span>Crear Barbero</span>
            </a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <!-- Mensaje de éxito -->
        @if (session('success'))
            <div id="success-message" class="bg-success text-light p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filtros de estado -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.barberos.index') }}" 
                   class="px-4 py-2 rounded transition-colors {{ !request('estado') ? 'bg-primary text-light' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    <i class="fas fa-users mr-1"></i>Todos
                </a>
                <a href="{{ route('admin.barberos.index', ['estado' => 'activos']) }}" 
                   class="px-4 py-2 rounded transition-colors {{ request('estado') === 'activos' ? 'bg-success text-light' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    <i class="fas fa-user-check mr-1"></i>Activos
                </a>
                <a href="{{ route('admin.barberos.index', ['estado' => 'inactivos']) }}" 
                   class="px-4 py-2 rounded transition-colors {{ request('estado') === 'inactivos' ? 'bg-warning text-light' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    <i class="fas fa-user-times mr-1"></i>Inactivos
                </a>
            </div>
            <div class="text-sm text-gray-600">
                Mostrando {{ $barberos->count() }} barbero{{ $barberos->count() !== 1 ? 's' : '' }}
                @if(request('estado'))
                    ({{ request('estado') }})
                @endif
            </div>
        </div>

        <div class="overflow-x-auto bg-surface">
            <table class="min-w-full bg-light border border-metal">
                <thead class="bg-secondary text-light">
                    <tr>
                        <th class="py-2 px-4 border-metal">ID</th>
                        <th class="py-2 px-4 border-metal">Estado</th>
                        <th class="py-2 px-4 border-metal">Nombre Completo</th>
                        <th class="py-2 px-4 border-metal">Email</th>
                        <th class="py-2 px-4 border-metal">Teléfono</th>
                        <th class="py-2 px-4 border-metal">Especialidad</th>
                        <th class="py-2 px-4 border-metal">Experiencia</th>
                        <th class="py-2 px-4 border-metal">Foto</th>
                        <th class="py-2 px-4 border-metal">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-secondary">
                    @forelse($barberos as $barbero)
                    <tr class="{{ !$barbero->activo ? 'bg-gray-100 opacity-75' : '' }}">
                        <td class="py-2 px-4 border-metal">{{ $barbero->id }}</td>
                        <td class="py-2 px-4 border-metal">
                            @if($barbero->activo)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Activo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Inactivo
                                </span>
                                @if($barbero->fecha_baja)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Desde: {{ $barbero->fecha_baja->format('d/m/Y') }}
                                    </div>
                                @endif
                            @endif
                        </td>
                        <td class="py-2 px-4 border-metal">{{ $barbero->nombre_completo }}</td>
                        <td class="py-2 px-4 border-metal">{{ $barbero->email }}</td>
                        <td class="py-2 px-4 border-metal">{{ $barbero->telefono }}</td>
                        <td class="py-2 px-4 border-metal">{{ $barbero->especialidad }}</td>
                        <td class="py-2 px-4 border-metal">{{ $barbero->experiencia }} años</td>
                        <td class="py-2 px-4 border-metal">
                            @if ($barbero->foto)
                                <img src="{{ asset('storage/' . $barbero->foto) }}" alt="Foto de {{ $barbero->nombre_completo }}" class="w-16 h-16 object-cover rounded">
                            @else
                                Sin foto
                            @endif
                        </td>
                        <td class="py-2 px-4 border-metal">
                            <div class="flex flex-col space-y-1 min-w-max">
                                <!-- Botón Editar -->
                                <a href="{{ route('admin.barberos.edit', $barbero->id) }}" 
                                   class="bg-primary hover:bg-secondary text-light py-1 px-3 rounded text-center text-sm transition-colors">
                                    <i class="fas fa-edit mr-1"></i>Editar
                                </a>
                                
                                @if($barbero->activo)
                                    <!-- Botón Dar de Baja -->
                                    <form action="{{ route('admin.barberos.dar_de_baja', $barbero->id) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="bg-warning hover:bg-yellow-600 text-white py-1 px-3 rounded w-full text-sm transition-colors"
                                                onclick="return confirm('¿Estás seguro de que deseas dar de baja a este barbero?\n\nEl barbero:\n• No podrá acceder al sistema\n• Se mantendrá su historial de citas\n• Podrá ser reactivado más tarde')">
                                            <i class="fas fa-user-times mr-1"></i>Dar de Baja
                                        </button>
                                    </form>
                                @else
                                    <!-- Botón Reactivar -->
                                    <form action="{{ route('admin.barberos.reactivar', $barbero->id) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="bg-success hover:bg-green-600 text-white py-1 px-3 rounded w-full text-sm transition-colors"
                                                onclick="return confirm('¿Estás seguro de que deseas reactivar a este barbero?\n\nEl barbero podrá volver a acceder al sistema.')">
                                            <i class="fas fa-user-check mr-1"></i>Reactivar
                                        </button>
                                    </form>
                                @endif
                                
                                <!-- Botón Eliminar Permanente -->
                                <form action="{{ route('admin.barberos.eliminar_permanente', $barbero->id) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-danger hover:bg-red-700 text-white py-1 px-3 rounded w-full text-sm transition-colors"
                                            onclick="return confirm('⚠️ ADVERTENCIA CRÍTICA ⚠️\n\nEsta acción eliminará PERMANENTEMENTE:\n• La cuenta de usuario del barbero\n• Su perfil completo\n• Todos sus datos personales\n\nSE MANTENDRÁN:\n• Las citas históricas (por integridad)\n\n❌ ESTA ACCIÓN NO SE PUEDE DESHACER ❌\n\n¿Estás COMPLETAMENTE seguro de continuar?')">
                                            <i class="fas fa-trash-alt mr-1"></i>Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-8 px-4 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users text-4xl mb-4 text-gray-300"></i>
                                @if(request('estado') === 'activos')
                                    <p class="text-lg font-medium">No hay barberos activos</p>
                                    <p class="text-sm">Todos los barberos están dados de baja o no hay barberos registrados.</p>
                                @elseif(request('estado') === 'inactivos')
                                    <p class="text-lg font-medium">No hay barberos inactivos</p>
                                    <p class="text-sm">Todos los barberos están activos.</p>
                                @else
                                    <p class="text-lg font-medium">No hay barberos registrados</p>
                                    <p class="text-sm">Comienza creando tu primer barbero.</p>
                                @endif
                                <a href="{{ route('admin.barberos.create') }}" 
                                   class="mt-4 bg-primary hover:bg-secondary text-light py-2 px-4 rounded flex items-center">
                                    <i class="fas fa-user-plus mr-2"></i>Crear Barbero
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener el elemento del mensaje de éxito
            const successMessage = document.getElementById('success-message');
            
            if (successMessage) {
                // Ocultar el mensaje después de 4 segundos
                setTimeout(() => {
                    successMessage.style.opacity = 0;
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                    }, 0); // Tiempo para desvanecerse
                }, 6000); // Tiempo de espera en milisegundos
            }
        });
    </script>
    @endpush
</x-app-layout>