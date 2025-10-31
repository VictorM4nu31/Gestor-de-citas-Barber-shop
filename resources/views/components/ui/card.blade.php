@props([
    'padding' => 'md',
    'shadow' => true,
    'border' => true,
    'class' => ''
])

@php
    $baseClasses = 'bg-surface rounded-lg';
    
    $paddingClasses = [
        'sm' => 'p-3',
        'md' => 'p-4',
        'lg' => 'p-6'
    ];
    
    $shadowClass = $shadow ? 'shadow-md' : '';
    $borderClass = $border ? 'border border-accent' : '';
    
    $classes = $baseClasses . ' ' . ($paddingClasses[$padding] ?? $paddingClasses['md']) . ' ' . $shadowClass . ' ' . $borderClass . ' ' . $class;
@endphp

<div class="{{ $classes }}" {{ $attributes }}>
    {{ $slot }}
</div>