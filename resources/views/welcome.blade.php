@extends('layouts.welcome')

@section('title', __('welcome.meta.title'))
@section('description', __('welcome.meta.description'))

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link href="https://cdn.jsdelivr.net/npm/flowbite@1.5.0/dist/flowbite.min.css" rel="stylesheet">
    
    <!-- Preload first gallery image for better performance -->
    @if($galleryImages->isNotEmpty())
        <link rel="preload" as="image" href="{{ $galleryImages->first()->thumbnail_url }}">
    @endif
@endpush

@section('content')
    <x-usuario.navbar />

    <main class="min-h-screen">
        <x-usuario.hero :title="__('welcome.hero.title')" :subtitle="__('welcome.hero.subtitle')" :primary_cta="__('welcome.hero.primary_cta')" :secondary_cta="__('welcome.hero.secondary_cta')" :image="asset('img/hero.jpg')" />
        <section id="about" class="text-center py-12 bg-secondary">
            <div class="container mx-auto px-4 lg:px-8 flex flex-col md:flex-row items-center">
                <div class="w-full md:w-1/2">
                    <h2 class="text-3xl font-semibold mb-4 text-primary">{{ __('welcome.about.title') }}</h2>
                    <p class="text-lg mb-8 text-muted">{{ __('welcome.about.description') }}</p>
                    <h3 class="text-2xl font-semibold mb-4 text-primary">{{ __('welcome.about.location_title') }}</h3>
                    <div id="mi_mapa" class="h-96 w-full rounded-lg overflow-hidden"></div>
                </div>
                <div class="w-full md:w-1/2 md:pl-8 mt-8 md:mt-0">
                    <h2 class="text-3xl font-semibold mb-4 text-primary">{{ __('welcome.about.info_title') }}</h2>
                    <p class="text-lg mb-4 text-muted">{{ __('welcome.about.address') }}</p>
                    <p class="text-lg mb-4 text-muted">{{ __('welcome.about.phone') }}</p>
                    <p class="text-lg text-muted">{{ __('welcome.about.schedule') }}</p>
                </div>
            </div>
        </section>

        <x-usuario.services :servicios="$servicios" />
        
        <!-- Gallery Section -->
        @if($galleryImages->isNotEmpty())
            <x-gallery.section 
                :title="__('welcome.gallery.title')"
                :images="$galleryImages"
                :columns="['mobile' => 1, 'tablet' => 2, 'desktop' => 3]"
                :lazy-load="true"
                :show-count="false"
                class="py-12 bg-secondary"
                id="gallery"
            >
                <x-slot:header>
                    <h2 class="text-3xl font-semibold mb-4 text-primary">{{ __('welcome.gallery.title') }}</h2>
                    <p class="text-lg mb-8 text-muted">{{ __('welcome.gallery.description') }}</p>
                </x-slot:header>
            </x-gallery.section>
        @endif

        <section id="barberos" class="text-center py-12 bg-background">
            <div class="container mx-auto px-4 lg:px-8">
                <h2 class="text-3xl font-semibold mb-4 text-secondary">{{ __('welcome.barberos.title') }}</h2>
                <p class="text-lg mb-8 text-muted">{{ __('welcome.barberos.description') }}</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach($barberos as $barbero)
                        <x-usuario.barbero-card :barbero="$barbero" />
                    @endforeach
                </div>
            </div>
        </section>

        <section id="contact" class="text-center py-12 bg-background">
            <div class="container mx-auto">
                <h2 class="text-3xl font-semibold mb-4 text-secondary">{{ __('welcome.contact.title') }}</h2>
                <p class="text-lg mb-8 text-muted">{{ __('welcome.contact.description') }}</p>
            </div>
        </section>
    </main>

    <!-- Gallery Lightbox -->
    @if($galleryImages->isNotEmpty())
        <x-gallery.lightbox :images="$galleryImages" />
    @endif

    <x-usuario.footer />
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.5.0/dist/flowbite.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let map = L.map('mi_mapa').setView([20.48621, -99.21711], 15);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([20.48623, -99.21709]).addTo(map).bindPopup("Barberia MasterCut").openPopup();
        });
    </script>
@endpush
