<section id="services" class="bg-background py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-8">
        <div class="border-b border-accent pb-10">
            <p class="eyebrow">02 / Servicios</p>
            <h2 class="display-title mt-3 text-4xl sm:text-6xl">{{ __('services.title') }}</h2>
            <p class="mt-4 max-w-xl text-lg text-muted">{{ __('services.description') }}</p>
        </div>

        @if(isset($servicios) && $servicios->count())
            <div class="mt-10 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($servicios as $servicio)
                    <article class="group flex flex-col overflow-hidden border border-accent bg-light transition hover:-translate-y-1 hover:border-primary">
                        @if($servicio->foto)
                            <div class="h-52 w-full overflow-hidden">
                                <img src="{{ asset('storage/' . $servicio->foto) }}" alt="{{ $servicio->getTranslatedName() }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            </div>
                        @else
                            <div class="flex h-52 items-end bg-secondary p-5 text-brass"><span class="text-6xl font-display">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span></div>
                        @endif

                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex items-start justify-between gap-4">
                                <h3 class="text-xl font-semibold text-secondary">{{ $servicio->getTranslatedName() }}</h3>
                                <span class="whitespace-nowrap text-sm font-bold text-primary">${{ number_format($servicio->precio, 2) }}</span>
                            </div>

                            <p class="mt-3 text-sm text-metal">{{ \Illuminate\Support\Str::limit($servicio->getTranslatedDescription(), 120) }}</p>

                            <div class="mt-6 flex items-center justify-between gap-4 border-t border-accent pt-4">
                                <small class="text-xs font-semibold uppercase tracking-wider text-muted">{{ $servicio->duracion }} {{ __('services.minutes') }}</small>
                                <a href="{{ auth()->check() ? route('citas.create') : route('login') }}" class="text-sm font-bold text-primary hover:text-secondary">{{ __('services.book') }} <span aria-hidden="true">→</span></a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="text-center text-muted mt-6">{{ __('services.no_services') }}</p>
        @endif
    </div>
</section>
