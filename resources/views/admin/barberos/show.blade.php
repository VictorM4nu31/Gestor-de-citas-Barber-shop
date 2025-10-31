<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ver Barbero</h2>
            <a href="{{ route('admin.barberos.index') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">Volver a la Lista</a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6 text-secondary">Ver Barbero</h1>
        <div class="bg-light border border-metal rounded-lg shadow-md p-6">
            <div class="mb-4">
                <strong class="text-secondary">Nombre Completo:</strong>
                <p class="text-muted">{{ $barbero->nombre_completo }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Email:</strong>
                <p class="text-muted">{{ $barbero->email }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Teléfono:</strong>
                <p class="text-muted">{{ $barbero->telefono }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Especialidad:</strong>
                <p class="text-muted">{{ $barbero->especialidad }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Experiencia:</strong>
                <p class="text-muted">{{ $barbero->experiencia }}</p>
            </div>
            @if ($barbero->foto)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $barbero->foto) }}" alt="Foto de {{ $barbero->nombre_completo }}" class="w-32 h-32 object-cover rounded-md border border-metal">
                </div>
            @endif
        </div>
    </main>
</x-app-layout>