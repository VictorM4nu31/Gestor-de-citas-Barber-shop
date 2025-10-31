<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $servicio->nombre }}</h2>
            <a href="{{ route('servicios.index') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">Volver a Servicios</a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-light border border-metal rounded-lg shadow-md p-8 max-w-4xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @if ($servicio->foto)
                    <div>
                        <img src="{{ asset('storage/' . $servicio->foto) }}" alt="Foto de {{ $servicio->nombre }}" class="w-full h-64 object-cover rounded-lg">
                    </div>
                @endif
                
                <div class="{{ $servicio->foto ? '' : 'lg:col-span-2' }}">
                    <h1 class="text-3xl font-semibold text-secondary mb-4">{{ $servicio->nombre }}</h1>
                    
                    <div class="space-y-4">
                        <div>
                            <strong class="text-secondary">Descripción:</strong>
                            <p class="text-muted mt-2">{{ $servicio->descripcion }}</p>
                        </div>
                        
                        <div class="flex justify-between items-center py-4 border-t border-metal">
                            <div>
                                <strong class="text-secondary">Precio:</strong>
                                <span class="text-2xl font-bold text-primary ml-2">${{ $servicio->precio }}</span>
                            </div>
                            <div>
                                <strong class="text-secondary">Duración:</strong>
                                <span class="text-lg text-muted ml-2">{{ $servicio->duracion }} minutos</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <a href="{{ route('citas.create') }}" class="bg-success hover:bg-primary text-light py-3 px-6 rounded-lg text-lg inline-block">Agendar este Servicio</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>