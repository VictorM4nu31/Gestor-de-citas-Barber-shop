@php
    // Get gallery images from database
    $galleryImages = \App\Models\GalleryImage::active()->ordered()->get();
@endphp

<x-gallery.section 
    title="Nuestra Galería"
    :images="$galleryImages"
    :columns="['mobile' => 2, 'tablet' => 3, 'desktop' => 4]"
    :show-count="false"
    :lazy-load="true"
    class="py-16"
>
    <x-slot:header>
        <h2 class="text-3xl font-bold text-foreground mb-4">Nuestra Galería</h2>
        <p class="text-muted-foreground max-w-2xl mx-auto">
            Descubre nuestro trabajo y el ambiente único de nuestro establecimiento
        </p>
    </x-slot:header>
</x-gallery.section>

<!-- Lightbox component -->
<x-gallery.lightbox :images="$galleryImages" />