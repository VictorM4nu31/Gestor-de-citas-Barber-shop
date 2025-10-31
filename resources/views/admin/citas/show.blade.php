<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Detalle de Cita</h2>
            <a href="{{ route('admin.citas.index') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">Volver a la Lista</a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-light p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4 text-secondary">Detalle de Cita #{{ $cita->id }}</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Información del Cliente</h3>
                    <div class="space-y-2">
                        <p><strong>Nombre:</strong> {{ $cita->nombre_completo }}</p>
                        <p><strong>Teléfono:</strong> {{ $cita->numero_telefono }}</p>
                        <p><strong>Email:</strong> {{ $cita->correo_electronico }}</p>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-2">Información de la Cita</h3>
                    <div class="space-y-2">
                        <p><strong>Fecha:</strong> {{ $cita->fecha }}</p>
                        <p><strong>Hora:</strong> {{ $cita->hora }}</p>
                        <p><strong>Barbero:</strong> {{ $cita->barbero->nombre_completo ?? 'No disponible' }}</p>
                        <p><strong>Costo Total:</strong> ${{ $cita->costo }}</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-2">Servicios Solicitados</h3>
                <p>{{ $cita->servicios_nombres_texto }}</p>
            </div>
            
            <div class="mt-6 flex space-x-4">
                <a href="{{ route('admin.citas.edit', $cita->id) }}" class="bg-info hover:bg-info/90 text-white py-2 px-4 rounded">Editar</a>
                <form action="{{ route('admin.citas.destroy', $cita->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-danger hover:bg-danger/90 text-white py-2 px-4 rounded" onclick="return confirm('¿Estás seguro de que deseas eliminar esta cita?')">Eliminar</button>
                </form>
            </div>
        </div>
    </main>
</x-app-layout>