<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Conoce a Nuestros Barberos</h2>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        @if($barberos->isEmpty())
            <div class="border border-dashed border-accent bg-light p-12 text-center">
                <p class="display-title text-3xl">Aún no hay barberos para mostrar.</p>
                <p class="mt-3 text-muted">Vuelve pronto o explora nuestros servicios.</p>
                <a href="{{ route('public.servicios.index') }}" class="mt-6 inline-block bg-primary px-6 py-3 font-bold text-light transition hover:bg-secondary">Ver servicios</a>
            </div>
        @else
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
        @endif
    </main>
</x-app-layout>