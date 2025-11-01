<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">{{ __('barberos.profile_of') }} {{ $barbero->nombre_completo }}</h2>
            <a href="{{ route('public.barberos.index') }}" class="bg-primary hover:bg-secondary text-white py-2 px-4 rounded">{{ __('barberos.back_to_list') }}</a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-background border border-accent rounded-lg shadow-md p-8 max-w-2xl mx-auto">
            @if ($barbero->foto)
                <div class="text-center mb-6">
                    <img src="{{ asset('storage/' . $barbero->foto) }}" alt="Foto de {{ $barbero->nombre_completo }}" class="w-48 h-48 object-cover rounded-full mx-auto border-4 border-primary">
                </div>
            @endif
            
            <h1 class="text-3xl font-semibold text-center text-secondary mb-6">{{ $barbero->nombre_completo }}</h1>
            
            <div class="space-y-4">
                <div>
                    <strong class="text-secondary">{{ __('barberos.email') }}:</strong>
                    <p class="text-muted">{{ $barbero->email }}</p>
                </div>
                
                @if($barbero->telefono)
                <div>
                    <strong class="text-secondary">{{ __('barberos.phone') }}:</strong>
                    <p class="text-muted">{{ $barbero->telefono }}</p>
                </div>
                @endif
                
                <div>
                    <strong class="text-secondary">{{ __('barberos.specialty') }}:</strong>
                    <p class="text-muted">{{ $barbero->getTranslatedEspecialidad() }}</p>
                </div>
                
                <div>
                    <strong class="text-secondary">{{ __('barberos.experience') }}:</strong>
                    <p class="text-muted">{{ $barbero->getTranslatedExperiencia() }}</p>
                </div>
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ route('citas.create') }}" class="bg-success hover:bg-primary text-white py-3 px-6 rounded-lg text-lg">{{ __('barberos.book_appointment_with') }} {{ $barbero->nombre_completo }}</a>
            </div>
        </div>
    </main>
</x-app-layout>