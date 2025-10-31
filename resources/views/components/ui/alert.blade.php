@props([
    'type' => 'info',
    'dismissible' => false,
    'class' => ''
])

@php
    $baseClasses = 'p-4 rounded-md border';
    
    $typeClasses = [
        'success' => 'bg-success/10 border-success text-success',
        'danger' => 'bg-danger/10 border-danger text-danger',
        'warning' => 'bg-warning/10 border-warning text-warning',
        'info' => 'bg-info/10 border-info text-info'
    ];
    
    $classes = $baseClasses . ' ' . ($typeClasses[$type] ?? $typeClasses['info']) . ' ' . $class;
    
    $alertId = 'alert-' . uniqid();
@endphp

<div id="{{ $alertId }}" class="{{ $classes }}" {{ $attributes }}>
    <div class="flex">
        <div class="flex-1">
            {{ $slot }}
        </div>
        
        @if($dismissible)
            <div class="ml-3">
                <button 
                    type="button" 
                    class="inline-flex rounded-md p-1.5 hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-offset-2"
                    onclick="document.getElementById('{{ $alertId }}').remove()"
                >
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif
    </div>
</div>