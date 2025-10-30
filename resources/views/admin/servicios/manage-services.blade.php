<main class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-4 md:space-y-0">
        <h1 class="text-3xl font-semibold text-secondary">Lista de Servicios</h1>
        <a href="{{ route('admin.servicios.create') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded flex items-center space-x-2">
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
                    <td class="py-2 px-4 border">{{ $servicio->nombre }}</td>
                    <td class="py-2 px-4 border">{{ $servicio->descripcion }}</td>
                    <td class="py-2 px-4 border">{{ $servicio->duracion }}</td>
                    <td class="py-2 px-4 border">{{ $servicio->precio }}</td>
                    <td class="py-2 px-4 border-metal">
                        @if ($servicio->foto)
                            <img src="{{ asset('storage/' . $servicio->foto) }}" alt="Foto de {{ $servicio->nombre }}" class="w-16 h-16 object-cover rounded">
                        @else
                            Sin foto
                        @endif
                    </td>
                    <td class="py-2 px-4 border-metal">
                        <a href="{{ route('admin.servicios.edit', $servicio->id) }}" class="bg-primary hover:bg-secondary text-light py-1 px-2 rounded">Editar</a>
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
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.opacity = 0;
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 4000);
            }, 6000);
        }
    });
</script>
@endpush
