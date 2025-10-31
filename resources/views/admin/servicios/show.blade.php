<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Detalle del Servicio</h2>
            <a href="{{ route('admin.servicios.index') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">Volver a la Lista</a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6 text-secondary">Ver Servicio</h1>
        <div class="bg-light border border-metal rounded-lg shadow-md p-6">
            <div class="mb-4">
                <strong class="text-secondary">Nombre:</strong>
                <p class="text-muted">{{ $servicio->nombre }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Descripción:</strong>
                <p class="text-muted">{{ $servicio->descripcion }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Duración:</strong>
                <p class="text-muted">{{ $servicio->duracion }} minutos</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Precio:</strong>
                <p class="text-muted">${{ $servicio->precio }}</p>
            </div>
            @if ($servicio->foto)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $servicio->foto) }}" alt="Foto de {{ $servicio->nombre }}" class="w-32 h-32 object-cover rounded-md border border-metal">
                </div>
            @endif
        </div>
    </main>
</x-app-layout>