@props([
    'padding' => 'md',
    'shadow' => true,
    'border' => true,
    'class' => ''
])

@php
    $baseClasses = 'bg-light rounded-md';
    
    $paddingClasses = [
        'sm' => 'p-3',
        'md' => 'p-4',
        'lg' => 'p-6'
    ];
    
    $shadowClass = $shadow ? 'shadow-[0_12px_32px_rgba(23,21,19,0.06)]' : '';
    $borderClass = $border ? 'border border-accent' : '';
    
    $classes = $baseClasses . ' ' . ($paddingClasses[$padding] ?? $paddingClasses['md']) . ' ' . $shadowClass . ' ' . $borderClass . ' ' . $class;
@endphp

<div class="{{ $classes }}" {{ $attributes }}>
    {{ $slot }}
</div>
