@php
    // Get gallery images from database
    $galleryImages = \App\Models\GalleryImage::active()->ordered()->get();
@endphp

<x-gallery.section 
    :title="__('gallery.title')"
    :images="$galleryImages"
    :columns="['mobile' => 2, 'tablet' => 3, 'desktop' => 4]"
    :show-count="false"
    :lazy-load="true"
    class="py-16"
>
    <x-slot:header>
        <h2 class="text-3xl font-bold text-foreground mb-4">{{ __('gallery.title') }}</h2>
        <p class="text-muted-foreground max-w-2xl mx-auto">
            {{ __('gallery.description') }}
        </p>
    </x-slot:header>
</x-gallery.section>

<!-- Lightbox component -->
<x-gallery.lightbox :images="$galleryImages" />