<section id="services" class="py-12 bg-surface">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-semibold mb-4 text-secondary">{{ __('services.title') }}</h2>
            <p class="text-lg mb-8 text-muted">{{ __('services.description') }}</p>
        </div>

        @if(isset($servicios) && $servicios->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
                @foreach($servicios as $servicio)
                    <div class="relative bg-secondary/5 rounded-2xl overflow-hidden shadow-lg border border-metal">
                        @if($servicio->foto)
                            <div class="h-44 w-full overflow-hidden">
                                <img src="{{ asset('storage/' . $servicio->foto) }}" alt="{{ $servicio->getTranslatedName() }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="h-44 w-full bg-gradient-to-r from-secondary via-secondary/80 to-secondary/60"></div>
                        @endif

                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-secondary">{{ $servicio->getTranslatedName() }}</h3>
                                <span class="text-sm font-medium bg-primary text-light px-3 py-1 rounded-full">${{ number_format($servicio->precio, 2) }}</span>
                            </div>

                            <p class="mt-3 text-sm text-metal">{{ \Illuminate\Support\Str::limit($servicio->getTranslatedDescription(), 120) }}</p>

                            <div class="mt-4 flex items-center justify-between">
                                <small class="text-xs text-muted">{{ __('services.duration') }}: {{ $servicio->duracion }} {{ __('services.minutes') }}</small>
                                <a href="#contact" class="inline-block bg-primary text-light text-sm font-medium py-2 px-4 rounded-lg">{{ __('services.book') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-muted mt-6">{{ __('services.no_services') }}</p>
        @endif
    </div>
</section>