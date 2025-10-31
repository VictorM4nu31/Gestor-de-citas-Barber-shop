@props([
    'size' => 'md',
    'class' => ''
])

@php
    $sizeClasses = [
        'sm' => 'h-6 w-auto',
        'md' => 'h-9 w-auto',
        'lg' => 'h-12 w-auto'
    ];
    
    $logoClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    
    // Determine the appropriate dashboard route based on user role
    $dashboardRoute = '#';
    if (auth()->check()) {
        if (auth()->user()->hasRole('admin')) {
            $dashboardRoute = route('admin.dashboard');
        } elseif (auth()->user()->hasRole('barbero')) {
            $dashboardRoute = route('barbero.dashboard');
        } else {
            $dashboardRoute = route('dashboard');
        }
    }
@endphp

<a href="{{ $dashboardRoute }}" class="inline-block {{ $class }}">
    <img 
        src="{{ asset('img/logo.png') }}" 
        alt="Barbería - Logo" 
        class="{{ $logoClass }} object-contain"
        loading="lazy"
    >
</a>