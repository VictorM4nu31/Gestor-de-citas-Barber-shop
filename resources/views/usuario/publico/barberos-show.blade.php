<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Perfil de {{ $barbero->nombre_completo }}</h2>
            <a href="{{ route('barberos.index') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">Volver a la Lista</a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-light border border-metal rounded-lg shadow-md p-8 max-w-2xl mx-auto">
            @if ($barbero->foto)
                <div class="text-center mb-6">
                    <img src="{{ asset('storage/' . $barbero->foto) }}" alt="Foto de {{ $barbero->nombre_completo }}" class="w-48 h-48 object-cover rounded-full mx-auto border-4 border-primary">
                </div>
            @endif
            
            <h1 class="text-3xl font-semibold text-center text-secondary mb-6">{{ $barbero->nombre_completo }}</h1>
            
            <div class="space-y-4">
                <div>
                    <strong class="text-secondary">Email:</strong>
                    <p class="text-muted">{{ $barbero->email }}</p>
                </div>
                
                @if($barbero->telefono)
                <div>
                    <strong class="text-secondary">Teléfono:</strong>
                    <p class="text-muted">{{ $barbero->telefono }}</p>
                </div>
                @endif
                
                <div>
                    <strong class="text-secondary">Especialidad:</strong>
                    <p class="text-muted">{{ $barbero->especialidad }}</p>
                </div>
                
                <div>
                    <strong class="text-secondary">Experiencia:</strong>
                    <p class="text-muted">{{ $barbero->experiencia }}</p>
                </div>
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ route('citas.create') }}" class="bg-success hover:bg-primary text-light py-3 px-6 rounded-lg text-lg">Agendar Cita con {{ $barbero->nombre_completo }}</a>
            </div>
        </div>
    </main>
</x-app-layout>