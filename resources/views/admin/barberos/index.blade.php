<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">{{ __('barberos.admin.titles.list') }}</h2>
            <a href="{{ route('admin.barberos.create') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded flex items-center space-x-2">
                <i class="fas fa-user-plus"></i>
                <span>{{ __('barberos.admin.buttons.create_barber') }}</span>
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
                   class="px-4 py-2 rounded transition-colors {{ !request('estado') ? 'bg-primary text-white' : 'bg-surface text-secondary hover:bg-accent' }}">
                    <i class="fas fa-users mr-1"></i>{{ __('barberos.admin.filters.all') }}
                </a>
                <a href="{{ route('admin.barberos.index', ['estado' => 'activos']) }}" 
                   class="px-4 py-2 rounded transition-colors {{ request('estado') === 'activos' ? 'bg-success text-white' : 'bg-surface text-secondary hover:bg-accent' }}">
                    <i class="fas fa-user-check mr-1"></i>{{ __('barberos.admin.filters.active') }}
                </a>
                <a href="{{ route('admin.barberos.index', ['estado' => 'inactivos']) }}" 
                   class="px-4 py-2 rounded transition-colors {{ request('estado') === 'inactivos' ? 'bg-warning text-white' : 'bg-surface text-secondary hover:bg-accent' }}">
                    <i class="fas fa-user-times mr-1"></i>{{ __('barberos.admin.filters.inactive') }}
                </a>
            </div>
            <div class="text-sm text-gray-600">
                {{ trans_choice('barberos.admin.filters.showing_count', $barberos->count(), ['count' => $barberos->count()]) }}
                @if(request('estado'))
                    ({{ request('estado') }})
                @endif
            </div>
        </div>

        <div class="overflow-x-auto bg-surface">
            <table class="min-w-full bg-light border border-metal">
                <thead class="bg-secondary text-light">
                    <tr>
                        <th class="py-2 px-4 border-metal">{{ __('barberos.admin.table.id') }}</th>
                        <th class="py-2 px-4 border-metal">{{ __('barberos.admin.table.status') }}</th>
                        <th class="py-2 px-4 border-metal">{{ __('barberos.admin.table.full_name') }}</th>
                        <th class="py-2 px-4 border-metal">{{ __('barberos.admin.table.email') }}</th>
                        <th class="py-2 px-4 border-metal">{{ __('barberos.admin.table.phone') }}</th>
                        <th class="py-2 px-4 border-metal">{{ __('barberos.admin.table.specialty') }}</th>
                        <th class="py-2 px-4 border-metal">{{ __('barberos.admin.table.experience') }}</th>
                        <th class="py-2 px-4 border-metal">{{ __('barberos.admin.table.photo') }}</th>
                        <th class="py-2 px-4 border-metal">{{ __('barberos.admin.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="text-secondary">
                    @forelse($barberos as $barbero)
                    <tr class="{{ !$barbero->activo ? 'bg-surface opacity-75' : '' }}">
                        <td class="py-2 px-4 border-metal">{{ $barbero->id }}</td>
                        <td class="py-2 px-4 border-metal">
                            @if($barbero->activo)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    {{ __('barberos.admin.status.active') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger/10 text-danger">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    {{ __('barberos.admin.status.inactive') }}
                                </span>
                                @if($barbero->fecha_baja)
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ __('barberos.admin.status.since') }}: {{ $barbero->fecha_baja->format('d/m/Y') }}
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
                                {{ __('barberos.admin.table.no_photo') }}
                            @endif
                        </td>
                        <td class="py-2 px-4 border-metal">
                            <div class="flex flex-col space-y-1 min-w-max">
                                <!-- Botón Editar -->
                                <a href="{{ route('admin.barberos.edit', $barbero->id) }}" 
                                   class="bg-primary hover:bg-secondary text-light py-1 px-3 rounded text-center text-sm transition-colors">
                                    <i class="fas fa-edit mr-1"></i>{{ __('barberos.admin.buttons.edit') }}
                                </a>
                                
                                @if($barbero->activo)
                                    <!-- Botón Dar de Baja -->
                                    <form action="{{ route('admin.barberos.dar_de_baja', $barbero->id) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="bg-warning hover:bg-warning/90 text-white py-1 px-3 rounded w-full text-sm transition-colors"
                                                onclick="return confirm('{{ __('barberos.admin.confirmations.deactivate') }}')">
                                            <i class="fas fa-user-times mr-1"></i>{{ __('barberos.admin.buttons.deactivate') }}
                                        </button>
                                    </form>
                                @else
                                    <!-- Botón Reactivar -->
                                    <form action="{{ route('admin.barberos.reactivar', $barbero->id) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="bg-success hover:bg-green-600 text-white py-1 px-3 rounded w-full text-sm transition-colors"
                                                onclick="return confirm('{{ __('barberos.admin.confirmations.reactivate') }}')">
                                            <i class="fas fa-user-check mr-1"></i>{{ __('barberos.admin.buttons.reactivate') }}
                                        </button>
                                    </form>
                                @endif
                                
                                <!-- Botón Eliminar Permanente -->
                                <form action="{{ route('admin.barberos.eliminar_permanente', $barbero->id) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-danger hover:bg-red-700 text-white py-1 px-3 rounded w-full text-sm transition-colors"
                                            onclick="return confirm('{{ __('users.confirmations.delete_barbero_critical') }}')">
                                            <i class="fas fa-trash-alt mr-1"></i>{{ __('barberos.admin.buttons.delete_permanent') }}
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
                                    <p class="text-lg font-medium">{{ __('barberos.admin.empty_states.no_active_barbers') }}</p>
                                    <p class="text-sm">{{ __('barberos.admin.empty_states.no_active_description') }}</p>
                                @elseif(request('estado') === 'inactivos')
                                    <p class="text-lg font-medium">{{ __('barberos.admin.empty_states.no_inactive_barbers') }}</p>
                                    <p class="text-sm">{{ __('barberos.admin.empty_states.no_inactive_description') }}</p>
                                @else
                                    <p class="text-lg font-medium">{{ __('barberos.admin.empty_states.no_barbers') }}</p>
                                    <p class="text-sm">{{ __('barberos.admin.empty_states.no_barbers_description') }}</p>
                                @endif
                                <a href="{{ route('admin.barberos.create') }}" 
                                   class="mt-4 bg-primary hover:bg-secondary text-light py-2 px-4 rounded flex items-center">
                                    <i class="fas fa-user-plus mr-2"></i>{{ __('barberos.admin.buttons.create_barber') }}
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