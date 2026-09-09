@props(['estado' => 'pendiente'])

@php
    $status = match ($estado) {
        'confirmada' => ['label' => 'Confirmada', 'color' => 'confirmed'],
        'atendida' => ['label' => 'Atendida', 'color' => 'attended'],
        'cancelada' => ['label' => 'Cancelada', 'color' => 'cancelled'],
        default => ['label' => 'Pendiente', 'color' => 'pending'],
    };
@endphp

<span class="inline-flex items-center gap-2 rounded-full border border-status-{{ $status['color'] }}/25 bg-status-{{ $status['color'] }}/10 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-status-{{ $status['color'] }}" aria-label="Estado: {{ $status['label'] }}">
    <span class="h-1.5 w-1.5 rounded-full bg-status-{{ $status['color'] }}" aria-hidden="true"></span>
    {{ $status['label'] }}
</span>
