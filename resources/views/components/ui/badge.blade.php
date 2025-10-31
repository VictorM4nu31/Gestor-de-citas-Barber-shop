@props([
    'type' => 'muted',
    'size' => 'md',
    'class' => ''
])

@php
    $baseClasses = 'inline-flex items-center font-medium rounded-full';
    
    $typeClasses = [
        'success' => 'bg-success/10 text-success border border-success/20',
        'danger' => 'bg-danger/10 text-danger border border-danger/20',
        'warning' => 'bg-warning/10 text-warning border border-warning/20',
        'info' => 'bg-info/10 text-info border border-info/20',
        'muted' => 'bg-muted/10 text-muted border border-muted/20',
        'primary' => 'bg-primary/10 text-primary border border-primary/20',
        'secondary' => 'bg-secondary/10 text-secondary border border-secondary/20'
    ];
    
    $sizeClasses = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-sm'
    ];
    
    $classes = $baseClasses . ' ' . ($typeClasses[$type] ?? $typeClasses['muted']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']) . ' ' . $class;
@endphp

<span class="{{ $classes }}" {{ $attributes }}>
    {{ $slot }}
</span>