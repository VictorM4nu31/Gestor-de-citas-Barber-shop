<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">Cita Programada</h2>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-light p-8 rounded-lg shadow-lg w-full max-w-3xl">
            <h1 class="text-2xl font-bold mb-6 text-secondary">Cita Programada</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="border border-metal rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-4 text-secondary">Detalles de la Cita</h2>
                    <p><span class="font-semibold">Fecha:</span> {{ $cita->fecha }}</p>
                    <p><span class="font-semibold">Hora:</span> {{ $cita->hora }}</p>
                    <p><span class="font-semibold">Barbero:</span> {{ $cita->barbero->nombre_completo ?? 'No disponible' }}</p>
                    <p><span class="font-semibold">Servicios:</span> {{ $cita->servicios_nombres_texto }}</p>
                    <p><span class="font-semibold">Costo:</span> ${{ $cita->costo }}</p>
                </div>
                <div class="border border-metal rounded-lg p-4 text-center">
                    <h2 class="text-lg font-semibold mb-4 text-secondary">Acciones</h2>
                    <a href="{{ route('citas.index') }}" class="w-full bg-light text-secondary border border-metal py-2 rounded-md mb-4 inline-block">Volver a Mis Citas</a>
                    <form action="{{ route('citas.destroy', $cita->id) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-danger hover:bg-secondary text-light py-2 rounded-md">Cancelar Cita</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
        document.querySelector('.delete-form').addEventListener('submit', function(event) {
            event.preventDefault();
            if (confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
                this.submit();
            }
        });
    </script>
    @endpush
</x-app-layout>