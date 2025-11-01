@props([
    'title' => 'Galería',
    'images' => collect(),
    'columns' => ['mobile' => 1, 'tablet' => 2, 'desktop' => 3],
    'showCount' => true,
    'lazyLoad' => true,
    'class' => ''
])

<section {{ $attributes->merge(['class' => "gallery-section bg-background py-8 {$class}"]) }}>
    <div class="container mx-auto px-4">
        @if(isset($header))
            <div class="gallery-header text-center mb-8">
                {{ $header }}
            </div>
        @else
            <div class="gallery-header text-center mb-8">
                <h2 class="text-3xl font-bold text-foreground mb-2">{{ $title }}</h2>
                @if($showCount && $images->count() > 0)
                    <p class="text-muted-foreground">{{ $images->count() }} {{ $images->count() === 1 ? 'imagen' : 'imágenes' }}</p>
                @endif
            </div>
        @endif

        @if($images->count() > 0)
            <x-gallery.grid 
                :images="$images" 
                :columns="$columns"
                :lazy-load="$lazyLoad"
            />
        @else
            <x-gallery.placeholder />
        @endif

        @if(isset($footer))
            <div class="gallery-footer mt-8">
                {{ $footer }}
            </div>
        @endif
    </div>
</section>