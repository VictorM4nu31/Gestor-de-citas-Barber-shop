@props([
    'images' => collect(),
    'showNavigation' => true,
    'showCounter' => true,
    'class' => ''
])

<div {{ $attributes->merge(['class' => "gallery-lightbox {$class}"]) }}
     x-data="galleryLightbox({{ $images->toJson() }})"
     x-show="isOpen"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
    x-on:open-lightbox.window="openLightbox($event.detail.index)"
    x-on:keydown.escape.window="closeLightbox()"
    x-on:keydown.arrow-left.window="previousImage()"
    x-on:keydown.arrow-right.window="nextImage()"
     style="display: none;"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm">
    
    <!-- Close button -->
    <button x-on:click="closeLightbox()" 
            class="absolute top-4 right-4 z-10 p-2 text-white hover:text-gray-300 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <span class="sr-only">{{ __('common.ui.close') }}</span>
    </button>
    
    @if($showCounter)
        <!-- Counter -->
        <div class="absolute top-4 left-4 z-10 px-3 py-1 bg-black/50 text-white text-sm rounded-full"
             x-show="images.length > 1">
            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
        </div>
    @endif
    
    <!-- Main image container -->
    <div class="relative max-w-full max-h-full p-4"
        x-on:click.away="closeLightbox()"
        x-on:touchstart="handleTouchStart($event)"
        x-on:touchmove="handleTouchMove($event)"
        x-on:touchend="handleTouchEnd($event)">
        
       <img :src="currentImage?.image_url" 
           :alt="currentImage?.alt_text || currentImage?.original_name || 'Imagen de galería'"
           class="max-w-full max-h-[90vh] object-contain"
           x-on:load="imageLoaded = true"
           x-on:error="imageError = true">
        
        <!-- Loading indicator -->
        <div x-show="!imageLoaded && !imageError" 
             class="absolute inset-0 flex items-center justify-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white"></div>
        </div>
        
        <!-- Error state -->
        <div x-show="imageError" 
             class="absolute inset-0 flex items-center justify-center text-white">
            <div class="text-center">
                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                    </path>
                </svg>
                <p>{{ __('gallery.errors.load_failed') }}</p>
            </div>
        </div>
    </div>
    
    @if($showNavigation)
        <!-- Navigation buttons -->
        <template x-if="images.length > 1">
            <div>
                <!-- Previous button -->
        <button x-on:click="previousImage()" 
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 p-2 text-white hover:text-gray-300 transition-colors"
                        :disabled="currentIndex === 0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="sr-only">{{ __('gallery.previous') }}</span>
                </button>
                
                <!-- Next button -->
        <button x-on:click="nextImage()" 
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 p-2 text-white hover:text-gray-300 transition-colors"
                        :disabled="currentIndex === images.length - 1">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="sr-only">{{ __('gallery.next') }}</span>
                </button>
            </div>
        </template>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('galleryLightbox', (images) => ({
        images: images || [],
        isOpen: false,
        currentIndex: 0,
        imageLoaded: false,
        imageError: false,
        touchStartX: 0,
        touchEndX: 0,
        
        get currentImage() {
            return this.images[this.currentIndex] || null;
        },
        
        openLightbox(index = 0) {
            this.currentIndex = Math.max(0, Math.min(index, this.images.length - 1));
            this.isOpen = true;
            this.imageLoaded = false;
            this.imageError = false;
            document.body.style.overflow = 'hidden';
        },
        
        closeLightbox() {
            this.isOpen = false;
            document.body.style.overflow = '';
        },
        
        nextImage() {
            if (this.currentIndex < this.images.length - 1) {
                this.currentIndex++;
                this.imageLoaded = false;
                this.imageError = false;
            }
        },
        
        previousImage() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
                this.imageLoaded = false;
                this.imageError = false;
            }
        },
        
        handleTouchStart(event) {
            this.touchStartX = event.touches[0].clientX;
        },
        
        handleTouchMove(event) {
            event.preventDefault();
        },
        
        handleTouchEnd(event) {
            this.touchEndX = event.changedTouches[0].clientX;
            this.handleSwipe();
        },
        
        handleSwipe() {
            const swipeThreshold = 50;
            const diff = this.touchStartX - this.touchEndX;
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe left - next image
                    this.nextImage();
                } else {
                    // Swipe right - previous image
                    this.previousImage();
                }
            }
        }
    }));
});
</script>
@endpush