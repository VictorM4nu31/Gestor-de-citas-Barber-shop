<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Nuestros Servicios</h2>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6 text-secondary">Servicios Disponibles</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($servicios as $servicio)
                <div class="bg-light border border-metal rounded-lg shadow-md p-6">
                    @if ($servicio->foto)
                        <img src="{{ asset('storage/' . $servicio->foto) }}" alt="Foto de {{ $servicio->nombre }}" class="w-full h-48 object-cover rounded-lg mb-4">
                    @endif
                    
                    <h3 class="text-xl font-semibold text-secondary mb-2">{{ $servicio->nombre }}</h3>
                    <p class="text-muted mb-3">{{ $servicio->descripcion }}</p>
                    
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-bold text-primary">${{ $servicio->precio }}</span>
                        <span class="text-sm text-muted">{{ $servicio->duracion }} min</span>
                    </div>
                    
                    <div class="text-center">
                        <a href="{{ route('servicios.show', $servicio->id) }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">Ver Detalles</a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('citas.create') }}" class="bg-success hover:bg-primary text-light py-3 px-6 rounded-lg text-lg">Agendar una Cita</a>
        </div>
    </main>
</x-app-layout>