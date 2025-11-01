<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">{{ __('barberos.admin.titles.show') }}</h2>
            <a href="{{ route('admin.barberos.index') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">{{ __('barberos.admin.buttons.back_to_list') }}</a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6 text-secondary">{{ __('barberos.admin.titles.show') }}</h1>
        <div class="bg-light border border-metal rounded-lg shadow-md p-6">
            <div class="mb-4">
                <strong class="text-secondary">{{ __('barberos.admin.labels.status') }}:</strong>
                @if($barbero->activo)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-success/10 text-success ml-2">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ __('barberos.admin.status.active') }}
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-danger/10 text-danger ml-2">
                        <i class="fas fa-times-circle mr-2"></i>
                        {{ __('barberos.admin.status.inactive') }}
                    </span>
                    @if($barbero->fecha_baja)
                        <p class="text-sm text-gray-500 mt-1">{{ __('barberos.admin.status.deactivated_on') }}: {{ $barbero->fecha_baja->format('d/m/Y H:i') }}</p>
                    @endif
                @endif
            </div>
            <div class="mb-4">
                <strong class="text-secondary">{{ __('barberos.admin.labels.full_name') }}:</strong>
                <p class="text-muted">{{ $barbero->nombre_completo }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">{{ __('barberos.admin.labels.email') }}:</strong>
                <p class="text-muted">{{ $barbero->email }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">{{ __('barberos.admin.labels.phone') }}:</strong>
                <p class="text-muted">{{ $barbero->telefono }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">{{ __('barberos.admin.labels.specialty') }}:</strong>
                <p class="text-muted">{{ $barbero->especialidad }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">{{ __('barberos.admin.labels.experience') }}:</strong>
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
            <h3 class="text-lg font-semibold text-secondary mb-4">{{ __('barberos.admin.titles.management_actions') }}</h3>
            <div class="flex flex-wrap gap-3">
                <!-- Botón Editar -->
                <a href="{{ route('admin.barberos.edit', $barbero->id) }}" 
                   class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded flex items-center">
                    <i class="fas fa-edit mr-2"></i>{{ __('barberos.admin.buttons.edit_barber') }}
                </a>
                
                @if($barbero->activo)
                    <!-- Botón Dar de Baja -->
                    <form action="{{ route('admin.barberos.dar_de_baja', $barbero->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="bg-warning hover:bg-warning/90 text-white py-2 px-4 rounded flex items-center"
                                onclick="return confirm('{{ __('barberos.admin.confirmations.deactivate_simple') }}')">
                            <i class="fas fa-user-times mr-2"></i>{{ __('barberos.admin.buttons.deactivate') }}
                        </button>
                    </form>
                @else
                    <!-- Botón Reactivar -->
                    <form action="{{ route('admin.barberos.reactivar', $barbero->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="bg-success hover:bg-success/90 text-white py-2 px-4 rounded flex items-center"
                                onclick="return confirm('{{ __('barberos.admin.confirmations.reactivate_simple') }}')">
                            <i class="fas fa-user-check mr-2"></i>{{ __('barberos.admin.buttons.reactivate_barber') }}
                        </button>
                    </form>
                @endif
                
                <!-- Botón Eliminar Permanente -->
                <form action="{{ route('admin.barberos.eliminar_permanente', $barbero->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-danger hover:bg-danger/90 text-white py-2 px-4 rounded flex items-center"
                            onclick="return confirm('{{ __('users.confirmations.delete_barbero_permanent') }}')">
                        <i class="fas fa-trash-alt mr-2"></i>{{ __('barberos.admin.buttons.delete_permanent') }}
                    </button>
                </form>
            </div>
        </div>
    </main>
</x-app-layout>