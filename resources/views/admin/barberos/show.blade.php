<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ver Barbero</h2>
            <a href="{{ route('admin.barberos.index') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">Volver a la Lista</a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6 text-secondary">Ver Barbero</h1>
        <div class="bg-light border border-metal rounded-lg shadow-md p-6">
            <div class="mb-4">
                <strong class="text-secondary">Estado:</strong>
                @if($barbero->activo)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 ml-2">
                        <i class="fas fa-check-circle mr-2"></i>
                        Activo
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 ml-2">
                        <i class="fas fa-times-circle mr-2"></i>
                        Inactivo
                    </span>
                    @if($barbero->fecha_baja)
                        <p class="text-sm text-gray-500 mt-1">Dado de baja el: {{ $barbero->fecha_baja->format('d/m/Y H:i') }}</p>
                    @endif
                @endif
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Nombre Completo:</strong>
                <p class="text-muted">{{ $barbero->nombre_completo }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Email:</strong>
                <p class="text-muted">{{ $barbero->email }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Teléfono:</strong>
                <p class="text-muted">{{ $barbero->telefono }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Especialidad:</strong>
                <p class="text-muted">{{ $barbero->especialidad }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Experiencia:</strong>
                <p class="text-muted">{{ $barbero->experiencia }}</p>
            </div>
            @if ($barbero->foto)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $barbero->foto) }}" alt="Foto de {{ $barbero->nombre_completo }}" class="w-32 h-32 object-cover rounded-md border border-metal">
                </div>
            @endif
        </div>

        <!-- Acciones de gestión -->
        <div class="mt-6 bg-light border border-metal rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-secondary mb-4">Acciones de Gestión</h3>
            <div class="flex flex-wrap gap-3">
                <!-- Botón Editar -->
                <a href="{{ route('admin.barberos.edit', $barbero->id) }}" 
                   class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded flex items-center">
                    <i class="fas fa-edit mr-2"></i>Editar Barbero
                </a>
                
                @if($barbero->activo)
                    <!-- Botón Dar de Baja -->
                    <form action="{{ route('admin.barberos.dar_de_baja', $barbero->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="bg-warning hover:bg-yellow-600 text-white py-2 px-4 rounded flex items-center"
                                onclick="return confirm('¿Estás seguro de que deseas dar de baja a este barbero? No podrá acceder al sistema pero se mantendrá su historial.')">
                            <i class="fas fa-user-times mr-2"></i>Dar de Baja
                        </button>
                    </form>
                @else
                    <!-- Botón Reactivar -->
                    <form action="{{ route('admin.barberos.reactivar', $barbero->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="bg-success hover:bg-green-600 text-white py-2 px-4 rounded flex items-center"
                                onclick="return confirm('¿Estás seguro de que deseas reactivar a este barbero?')">
                            <i class="fas fa-user-check mr-2"></i>Reactivar Barbero
                        </button>
                    </form>
                @endif
                
                <!-- Botón Eliminar Permanente -->
                <form action="{{ route('admin.barberos.eliminar_permanente', $barbero->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-danger hover:bg-red-700 text-white py-2 px-4 rounded flex items-center"
                            onclick="return confirm('⚠️ ADVERTENCIA: Esta acción eliminará PERMANENTEMENTE al barbero y todos sus datos.\n\n• Se eliminará su cuenta de usuario\n• Se eliminará su perfil de barbero\n• Se mantendrán las citas históricas por integridad\n\n¿Estás COMPLETAMENTE seguro de continuar? Esta acción NO se puede deshacer.')">
                        <i class="fas fa-trash-alt mr-2"></i>Eliminar Permanentemente
                    </button>
                </form>
            </div>
        </div>
    </main>
</x-app-layout>