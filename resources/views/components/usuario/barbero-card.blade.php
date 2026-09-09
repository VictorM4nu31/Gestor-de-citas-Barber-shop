@props(['barbero'])

<a href="{{ route('public.barberos.show', $barbero) }}" class="group flex flex-col items-center border border-accent bg-light p-5 text-center transition hover:-translate-y-1 hover:border-primary">
    @if($barbero->foto)
        <img class="h-40 w-full object-cover grayscale transition duration-500 group-hover:grayscale-0" src="{{ asset('storage/' . $barbero->foto) }}" alt="{{ $barbero->nombre_completo }}">
    @else
        <div class="flex h-40 w-full items-center justify-center bg-secondary text-5xl font-display text-brass">{{ mb_substr($barbero->nombre_completo, 0, 1) }}</div>
    @endif
    <h3 class="mt-5 text-lg font-semibold text-secondary">{{ $barbero->nombre_completo }}</h3>
    <p class="mt-1 text-sm text-muted">{{ $barbero->getTranslatedEspecialidad() }}</p>
</a>
