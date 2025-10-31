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
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-4 md:space-y-0">
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
                    @foreach($barberos as $barbero)
                    <tr>
                        <td class="py-2 px-4 border-metal">{{ $barbero->id }}</td>
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
                        <td class="py-2 px-4 border-metal flex flex-col space-y-2 md:space-y-0 md:flex-row md:space-x-2">
                            <a href="{{ route('admin.barberos.edit', $barbero->id) }}" class="bg-primary hover:bg-secondary text-light py-1 px-2 rounded">Editar</a>
                            <form action="{{ route('admin.barberos.destroy', $barbero->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-danger hover:bg-secondary text-light py-1 px-2 rounded" onclick="return confirm('¿Estás seguro de que deseas eliminar este barbero?')">Eliminar</button>
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