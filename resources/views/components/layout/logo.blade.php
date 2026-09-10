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
    $dashboardRoute = route('home'); // Default to home page
    if (auth()->check() && auth()->user()) {
        try {
            if (auth()->user()->hasRole('admin')) {
                $dashboardRoute = route('admin.dashboard');
            } elseif (auth()->user()->hasRole('barbero')) {
                $dashboardRoute = route('barbero.dashboard');
            } else {
                $dashboardRoute = route('dashboard');
            }
        } catch (\Exception $e) {
            // If there's an error with roles, default to home
            $dashboardRoute = route('home');
        }
    }
@endphp

<a href="{{ $dashboardRoute }}" class="inline-block {{ $class }}">
    <img
        src="{{ asset('img/mastercut-mark.svg') }}"
        alt="MasterCut - Inicio"
        class="{{ $logoClass }} object-contain"
        loading="lazy"
    >
</a>
