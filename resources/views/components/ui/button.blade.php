@props([
    'type' => 'primary',
    'size' => 'md',
    'href' => null,
    'disabled' => false,
    'submit' => false,
    'class' => ''
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $typeClasses = [
        'primary' => 'bg-primary hover:bg-primary/90 text-white focus:ring-primary',
        'secondary' => 'bg-secondary hover:bg-secondary/90 text-white focus:ring-secondary',
        'danger' => 'bg-danger hover:bg-danger/90 text-white focus:ring-danger',
        'success' => 'bg-success hover:bg-success/90 text-white focus:ring-success',
        'warning' => 'bg-warning hover:bg-warning/90 text-black focus:ring-warning',
        'info' => 'bg-info hover:bg-info/90 text-white focus:ring-info'
    ];
    
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg'
    ];
    
    $classes = $baseClasses . ' ' . ($typeClasses[$type] ?? $typeClasses['primary']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']) . ' ' . $class;
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $classes }}" {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <button 
        type="{{ $submit ? 'submit' : 'button' }}" 
        class="{{ $classes }}" 
        @if($disabled) disabled @endif
        {{ $attributes }}
    >
        {{ $slot }}
    </button>
@endif