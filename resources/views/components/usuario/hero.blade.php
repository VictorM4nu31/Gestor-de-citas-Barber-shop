<section id="home" class="overflow-hidden bg-secondary text-light">
    <div class="mx-auto grid max-w-7xl items-end gap-10 px-4 py-16 sm:px-8 lg:grid-cols-[1.05fr_.95fr] lg:py-24">
        <div class="relative z-10 max-w-3xl">
            <p class="eyebrow text-brass">{{ __('welcome.hero.premium_text') }}</p>
            <h1 class="display-title mt-5 text-6xl leading-[.88] text-light sm:text-8xl">{{ $title ?? 'Crafted to Perfection' }}</h1>
            <p class="mt-8 max-w-xl text-lg leading-relaxed text-light/70">{{ $subtitle ?? 'Experience the art of traditional barbering combined with modern techniques. Our master barbers deliver precision cuts and grooming services that define excellence.' }}</p>

            <div class="mt-10 flex flex-wrap gap-3">
                <a href="{{ auth()->check() ? route('citas.create') : route('login') }}" class="inline-flex items-center gap-3 bg-brass px-6 py-4 font-bold text-secondary transition hover:bg-light">{{ $primary_cta ?? 'Book Appointment' }} <span aria-hidden="true">→</span></a>
                @if(isset($secondary_cta))
                    <a href="#services" class="inline-flex items-center border border-light/40 px-6 py-4 font-bold text-light transition hover:border-light">{{ $secondary_cta }}</a>
                @endif
            </div>
        </div>

        <div class="relative lg:pt-10">
            <div class="absolute -bottom-6 -left-6 hidden h-32 w-32 border border-brass/50 lg:block"></div>
            <div class="relative aspect-[4/5] overflow-hidden border border-light/20">
                @if(isset($image))
                    <img src="{{ $image }}" alt="Hero" class="h-full w-full object-cover">
                @else
                    <img src="{{ asset('img/prueba.jpeg') }}" alt="Hero" class="h-full w-full object-cover">
                @endif
            </div>
        </div>
    </div>
</section>
