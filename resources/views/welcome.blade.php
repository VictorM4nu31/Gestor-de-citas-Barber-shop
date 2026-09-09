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
        <x-usuario.hero :title="__('welcome.hero.title')" :subtitle="__('welcome.hero.subtitle')" :primary_cta="__('welcome.hero.primary_cta')" :secondary_cta="__('welcome.hero.secondary_cta')" />
        <section id="about" class="bg-secondary py-20 text-light">
            <div class="mx-auto flex max-w-7xl flex-col gap-10 px-4 sm:px-8 md:flex-row md:items-end">
                <div class="w-full md:w-3/5">
                    <p class="eyebrow text-brass">02 / The place</p>
                    <h2 class="display-title mt-4 text-4xl text-light sm:text-6xl">{{ __('welcome.about.title') }}</h2>
                    <p class="mt-6 max-w-xl text-lg leading-relaxed text-light/70">{{ __('welcome.about.description') }}</p>
                    <div id="mi_mapa" class="mt-8 h-72 w-full overflow-hidden border border-light/20 grayscale"></div>
                </div>
                <div class="w-full border-l border-brass/50 pl-6 md:w-2/5 md:pl-10">
                    <p class="eyebrow text-brass">{{ __('welcome.about.info_title') }}</p>
                    <div class="mt-6 space-y-4 text-lg text-light/80">
                        <p>{{ __('welcome.about.address') }}</p>
                        <p>{{ __('welcome.about.phone') }}</p>
                        <p>{{ __('welcome.about.schedule') }}</p>
                    </div>
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
                class="bg-background py-20"
                id="gallery"
            >
                <x-slot:header>
                    <p class="eyebrow">03 / Lookbook</p>
                    <h2 class="display-title mt-3 text-4xl text-secondary sm:text-6xl">{{ __('welcome.gallery.title') }}</h2>
                    <p class="mt-4 max-w-xl text-lg text-muted">{{ __('welcome.gallery.description') }}</p>
                </x-slot:header>
            </x-gallery.section>
        @endif

        <section id="barberos" class="bg-paper py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-8">
                <p class="eyebrow">04 / The team</p>
                <h2 class="display-title mt-3 text-4xl text-secondary sm:text-6xl">{{ __('welcome.barberos.title') }}</h2>
                <p class="mt-4 max-w-xl text-lg text-muted">{{ __('welcome.barberos.description') }}</p>
                <div class="mt-10 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach($barberos as $barbero)
                        <x-usuario.barbero-card :barbero="$barbero" />
                    @endforeach
                </div>
            </div>
        </section>

        <section id="contact" class="bg-brass py-16">
            <div class="mx-auto flex max-w-7xl flex-col justify-between gap-8 px-4 sm:px-8 md:flex-row md:items-end">
                <div>
                    <p class="eyebrow text-secondary">05 / Make time</p>
                    <h2 class="display-title mt-3 max-w-2xl text-4xl text-secondary sm:text-6xl">{{ __('welcome.contact.title') }}</h2>
                    <p class="mt-4 max-w-xl text-lg text-secondary/75">{{ __('welcome.contact.description') }}</p>
                </div>
                <a href="{{ auth()->check() ? route('citas.create') : route('login') }}" class="inline-flex items-center justify-center gap-3 bg-secondary px-6 py-4 font-bold text-light transition hover:bg-primary">{{ __('welcome.hero.primary_cta') }} <span aria-hidden="true">→</span></a>
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
