@props(['barbero'])

<div class="bg-light border border-metal rounded-md shadow p-4 flex flex-col items-center">
    @if($barbero->foto)
        <img class="rounded-full h-36 w-36 object-cover" src="{{ asset('storage/' . $barbero->foto) }}" alt="{{ $barbero->nombre_completo }}">
    @else
        <img class="rounded-full h-36 w-36 object-cover" src="https://via.placeholder.com/150" alt="{{ $barbero->nombre_completo }}">
    @endif
    <h3 class="text-secondary mt-4 text-lg font-medium">{{ $barbero->nombre_completo }}</h3>
    <p class="text-muted">{{ $barbero->especialidad }}</p>
</div>
