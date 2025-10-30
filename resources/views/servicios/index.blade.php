<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Lista de Servicios</h2>
            <a href="{{ route('servicios.create') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded flex items-center space-x-2">
                <i class="fas fa-plus-circle"></i>
                <span>Crear Servicio</span>
            </a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-4 md:space-y-0">
            <h1 class="text-3xl font-semibold text-secondary">Lista de Servicios</h1>
            <a href="{{ route('servicios.create') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded flex items-center space-x-2">
                <i class="fas fa-plus-circle"></i>
                <span>Crear Servicio</span>
            </a>
        </div>
        <!-- Mensaje de éxito -->
        @if (session('success'))
            <div id="success-message" class="bg-success text-light p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-surface">
            <table class="min-w-full bg-light border border-metal">
                <thead class="bg-secondary text-light">
                    <tr>
                        <th class="py-2 px-4 border-metal">ID</th>
                        <th class="py-2 px-4 border-metal">Nombre</th>
                        <th class="py-2 px-4 border-metal">Descripción</th>
                        <th class="py-2 px-4 border-metal">Duración (min)</th>
                        <th class="py-2 px-4 border-metal">Precio</th>
                        <th class="py-2 px-4 border-metal">Foto</th>
                        <th class="py-2 px-4 border-metal">Acciones</th>
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
                                Sin foto
                            @endif
                        </td>
                        <td class="py-2 px-4 border-metal">
                            <a href="{{ route('servicios.edit', $servicio->id) }}" class="bg-primary hover:bg-secondary text-light py-1 px-2 rounded">Editar</a>
                            <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-danger hover:bg-secondary text-light py-1 px-2 rounded" onclick="return confirm('¿Estás seguro de que deseas eliminar este servicio?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
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
