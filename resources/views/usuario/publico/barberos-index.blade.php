<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Nuestros Barberos</h2>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6 text-secondary">Conoce a Nuestros Barberos</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($barberos as $barbero)
                <div class="bg-background border border-accent rounded-lg shadow-md p-6">
                    @if ($barbero->foto)
                        <img src="{{ asset('storage/' . $barbero->foto) }}" alt="Foto de {{ $barbero->nombre_completo }}" class="w-32 h-32 object-cover rounded-full mx-auto mb-4">
                    @else
                        <div class="w-32 h-32 bg-surface rounded-full mx-auto mb-4 flex items-center justify-center">
                            <span class="text-muted">Sin foto</span>
                        </div>
                    @endif
                    
                    <h3 class="text-xl font-semibold text-center text-secondary mb-2">{{ $barbero->nombre_completo }}</h3>
                    <p class="text-center text-muted mb-2"><strong>Especialidad:</strong> {{ $barbero->especialidad }}</p>
                    <p class="text-center text-muted mb-4"><strong>Experiencia:</strong> {{ $barbero->experiencia }}</p>
                    
                    <div class="text-center">
                        <a href="{{ route('public.barberos.show', $barbero->id) }}" class="bg-primary hover:bg-secondary text-white py-2 px-4 rounded">{{ __('users.actions.view_profile') }}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </main>
</x-app-layout>