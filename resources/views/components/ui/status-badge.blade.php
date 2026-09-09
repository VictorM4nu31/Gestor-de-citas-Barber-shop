@props(['estado' => 'pendiente'])

@php
    $status = match ($estado) {
        'confirmada' => ['label' => 'Confirmada', 'classes' => 'border-status-confirmed/25 bg-status-confirmed/10 text-status-confirmed', 'dot' => 'bg-status-confirmed'],
        'atendida' => ['label' => 'Atendida', 'classes' => 'border-status-attended/25 bg-status-attended/10 text-status-attended', 'dot' => 'bg-status-attended'],
        'cancelada' => ['label' => 'Cancelada', 'classes' => 'border-status-cancelled/25 bg-status-cancelled/10 text-status-cancelled', 'dot' => 'bg-status-cancelled'],
        default => ['label' => 'Pendiente', 'classes' => 'border-status-pending/25 bg-status-pending/10 text-status-pending', 'dot' => 'bg-status-pending'],
    };
@endphp

<span class="inline-flex items-center gap-2 rounded-full px-2.5 py-1 text-xs font-bold uppercase tracking-wider {{ $status['classes'] }}" aria-label="Estado: {{ $status['label'] }}">
    <span class="h-1.5 w-1.5 rounded-full {{ $status['dot'] }}" aria-hidden="true"></span>
    {{ $status['label'] }}
</span>
