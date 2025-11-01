@props([
    'images' => collect(),
    'columns' => ['mobile' => 1, 'tablet' => 2, 'desktop' => 3],
    'aspectRatio' => 'auto',
    'lazyLoad' => true,
    'gap' => 4,
    'class' => ''
])

@php
    $mobileColumns = $columns['mobile'] ?? 1;
    $tabletColumns = $columns['tablet'] ?? 2;
    $desktopColumns = $columns['desktop'] ?? 3;
    
    $gridClasses = "grid gap-{$gap} ";
    $gridClasses .= "grid-cols-{$mobileColumns} ";
    $gridClasses .= "md:grid-cols-{$tabletColumns} ";
    $gridClasses .= "lg:grid-cols-{$desktopColumns}";
@endphp

<div {{ $attributes->merge(['class' => "gallery-grid {$gridClasses} {$class}"]) }}
     @if($lazyLoad) x-data="galleryGrid" @endif>
    
    @foreach($images as $index => $image)
        <x-gallery.image-item 
            :image="$image"
            :index="$index"
            :lazy-load="$lazyLoad"
            :aspect-ratio="$aspectRatio"
            class="gallery-item"
            @click="openLightbox({{ $index }})"
        />
    @endforeach
</div>

@if($lazyLoad)
    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('galleryGrid', () => ({
                init() {
                    this.setupLazyLoading();
                },
                
                setupLazyLoading() {
                    if ('IntersectionObserver' in window) {
                        const imageObserver = new IntersectionObserver((entries, observer) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    const img = entry.target;
                                    const src = img.dataset.src;
                                    if (src) {
                                        img.src = src;
                                        img.classList.remove('lazy');
                                        img.classList.add('loaded');
                                        observer.unobserve(img);
                                    }
                                }
                            });
                        }, {
                            rootMargin: '50px 0px',
                            threshold: 0.01
                        });

                        document.querySelectorAll('img[data-src]').forEach(img => {
                            imageObserver.observe(img);
                        });
                    } else {
                        // Fallback for browsers without IntersectionObserver
                        document.querySelectorAll('img[data-src]').forEach(img => {
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            img.classList.add('loaded');
                        });
                    }
                },

                openLightbox(index) {
                    this.$dispatch('open-lightbox', { index });
                }
            }));
        });
    </script>
    @endpush
@endif