@props([
    'image',
    'index' => 0,
    'lazyLoad' => true,
    'aspectRatio' => 'auto',
    'showOverlay' => true,
    'clickable' => true,
    'class' => ''
])

@php
    $imageUrl = $image->image_url ?? '';
    $thumbnailUrl = $image->thumbnail_url ?? $imageUrl;
    $altText = $image->alt_text ?? $image->original_name ?? 'Imagen de galería';
    
    $aspectRatioClass = match($aspectRatio) {
        'square' => 'aspect-square',
        '4/3' => 'aspect-[4/3]',
        '3/2' => 'aspect-[3/2]',
        '16/9' => 'aspect-video',
        default => ''
    };
@endphp

<div {{ $attributes->merge(['class' => "gallery-image-item relative overflow-hidden rounded-lg bg-muted group {$aspectRatioClass} {$class}"]) }}
     @if($clickable) 
        role="button" 
        tabindex="0"
        @keydown.enter="$dispatch('click')"
        @keydown.space.prevent="$dispatch('click')"
     @endif>
    
    <!-- Image -->
    <img 
        @if($lazyLoad)
            data-src="{{ $thumbnailUrl }}"
            src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 300'%3E%3Crect width='400' height='300' fill='%23f3f4f6'/%3E%3C/svg%3E"
            class="lazy w-full h-full object-cover transition-all duration-300 group-hover:scale-105"
        @else
            src="{{ $thumbnailUrl }}"
            class="loaded w-full h-full object-cover transition-all duration-300 group-hover:scale-105"
        @endif
        alt="{{ $altText }}"
        loading="lazy"
    />
    
    <!-- Loading placeholder -->
    <div class="absolute inset-0 bg-muted animate-pulse lazy-placeholder">
        <div class="flex items-center justify-center h-full">
            <svg class="w-8 h-8 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                </path>
            </svg>
        </div>
    </div>
    
    @if($showOverlay && $clickable)
        <!-- Hover overlay -->
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-300 flex items-center justify-center">
            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7">
                    </path>
                </svg>
            </div>
        </div>
    @endif
    
    @if($clickable)
        <!-- Click indicator for accessibility -->
        <span class="sr-only">Hacer clic para ver imagen en tamaño completo</span>
    @endif
</div>

@push('styles')
<style>
    .gallery-image-item img.lazy {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .gallery-image-item img.loaded {
        opacity: 1;
    }
    
    .gallery-image-item img.loaded + .lazy-placeholder {
        display: none;
    }
    
    .gallery-image-item:focus {
        outline: 2px solid theme('colors.primary');
        outline-offset: 2px;
    }
</style>
@endpush