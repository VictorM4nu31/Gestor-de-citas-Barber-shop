<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">{{ __('admin.titles.service_list') }}</h2>
            <a href="{{ route('admin.servicios.create') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded flex items-center space-x-2">
                <i class="fas fa-plus-circle"></i>
                <span>{{ __('admin.buttons.create_service') }}</span>
            </a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-4 md:space-y-0">
            <h1 class="text-3xl font-semibold text-secondary">{{ __('admin.titles.service_list') }}</h1>
            <a href="{{ route('admin.servicios.create') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded flex items-center space-x-2">
                <i class="fas fa-plus-circle"></i>
                <span>{{ __('admin.buttons.create_service') }}</span>
            </a>
        </div>
        <!-- Mensaje de éxito -->
        @if (session('success'))
            <div id="success-message" class="bg-success text-light p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="hidden overflow-x-auto bg-surface md:block">
            <table class="min-w-full bg-light border border-metal">
                <thead class="bg-secondary text-light">
                    <tr>
                        <th class="py-2 px-4 border-metal">ID</th>
                        <th class="py-2 px-4 border-metal">{{ __('admin.labels.name') }}</th>
                        <th class="py-2 px-4 border-metal">{{ __('admin.labels.description') }}</th>
                        <th class="py-2 px-4 border-metal">{{ __('admin.labels.duration') }}</th>
                        <th class="py-2 px-4 border-metal">{{ __('admin.labels.price') }}</th>
                        <th class="py-2 px-4 border-metal">{{ __('admin.labels.photo') }}</th>
                        <th class="py-2 px-4 border-metal">{{ __('admin.labels.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="text-secondary">
                    @foreach($servicios as $servicio)
                    <tr>
                        <td class="py-2 px-4 border-metal">{{ $servicio->id }}</td>
                        <td class="py-2 px-4 border-metal">{{ $servicio->nombre }}</td>
                        <td class="py-2 px-4 border-metal">{{ $servicio->descripcion }}</td>
                        <td class="py-2 px-4 border-metal">{{ $servicio->duracion }}</td>
                        <td class="py-2 px-4 border-metal">{{ $servicio->precio }}</td>
                        <td class="py-2 px-4 border-metal">
                            @if ($servicio->foto)
                                <img src="{{ asset('storage/' . $servicio->foto) }}" alt="Foto de {{ $servicio->nombre }}" class="w-16 h-16 object-cover rounded">
                            @else
                                {{ __('admin.labels.no_photo') }}
                            @endif
                        </td>
                        <td class="py-2 px-4 border-metal">
                            <a href="{{ route('admin.servicios.edit', $servicio->id) }}" class="bg-primary hover:bg-secondary text-light py-1 px-2 rounded">{{ __('admin.buttons.edit') }}</a>
                            <form action="{{ route('admin.servicios.destroy', $servicio->id) }}" method="POST" class="inline-block" x-data x-on:confirmed-delete-service-{{ $servicio->id }}.window="$el.submit()">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="bg-danger hover:bg-secondary text-light py-1 px-2 rounded" @click="$dispatch('open-modal-delete-service-{{ $servicio->id }}', { trigger: $el })">{{ __('admin.buttons.delete') }}</button>
                            </form>
                            <x-ui.confirm-modal id="delete-service-{{ $servicio->id }}" title="Eliminar {{ $servicio->nombre }}" message="Esta acción eliminará el servicio de forma permanente." />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="space-y-3 md:hidden">
            @forelse($servicios as $servicio)
                <article class="border border-accent bg-light p-4">
                    <div class="flex items-start justify-between gap-3">
                        <h3 class="font-bold text-secondary">{{ $servicio->nombre }}</h3>
                        <span class="font-bold text-primary">${{ number_format($servicio->precio, 2) }}</span>
                    </div>
                    <p class="mt-2 text-sm text-muted">{{ $servicio->descripcion }}</p>
                    <div class="mt-3 flex justify-between text-sm text-muted">
                        <span>{{ $servicio->duracion }} min</span>
                        <span>{{ $servicio->publicado ? 'Publicado' : 'Oculto' }}</span>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('admin.servicios.edit', $servicio) }}" class="bg-primary px-3 py-2 text-sm font-bold text-light">Editar</a>
                        <form action="{{ route('admin.servicios.destroy', $servicio) }}" method="POST" class="inline" x-data x-on:confirmed-delete-service-mobile-{{ $servicio->id }}.window="$el.submit()">
                            @csrf
                            @method('DELETE')
                            <button type="button" @click="$dispatch('open-modal-delete-service-mobile-{{ $servicio->id }}', { trigger: $el })" class="border border-danger px-3 py-2 text-sm font-bold text-danger">Eliminar</button>
                        </form>
                        <x-ui.confirm-modal id="delete-service-mobile-{{ $servicio->id }}" title="Eliminar {{ $servicio->nombre }}" message="Esta acción es permanente." />
                    </div>
                </article>
            @empty
                <p class="border border-dashed border-accent p-6 text-center text-muted">No hay servicios.</p>
            @endforelse
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
