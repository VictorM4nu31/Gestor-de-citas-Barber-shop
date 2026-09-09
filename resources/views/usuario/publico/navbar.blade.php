<nav class="border-b border-secondary/10 bg-background px-4 text-secondary sm:px-8" x-data="{ open: false }">
    <div class="mx-auto flex min-h-20 max-w-7xl items-center justify-between gap-6">
        <div class="md:order-1">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('img/logo.png') }}" class="h-10 w-10 object-contain" alt="Logo">
                <span class="hidden text-sm font-bold uppercase tracking-[0.18em] sm:block">MasterCut</span>
            </a>
        </div>
        <div class="hidden md:block">
            <ul class="flex items-center gap-8 text-sm font-semibold">
                <li><a class="transition-colors hover:text-primary" href="{{ route('home') }}#services">{{ __('services.title') }}</a></li>
                <li><a class="transition-colors hover:text-primary" href="{{ route('home') }}#barberos">{{ __('welcome.barberos.title') }}</a></li>
                <li><a class="transition-colors hover:text-primary" href="{{ route('home') }}#gallery">{{ __('welcome.gallery.title') }}</a></li>
            </ul>
        </div>
        <div class="flex items-center gap-3">
            {{-- Language Switcher --}}
            <x-shared.language-switcher />

            <a href="{{ route('login') }}" class="border border-secondary px-4 py-2 text-sm font-bold transition hover:bg-secondary hover:text-light">
                {{ __('common.navigation.login') }}
            </a>
            <button @click="open = ! open" type="button" class="inline-flex items-center justify-center p-2 transition hover:text-primary md:hidden" aria-label="Menú" :aria-expanded="open">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path :class="{ 'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
    <div x-show="open" x-cloak class="pb-4 md:hidden">
        <ul class="space-y-1 text-sm font-semibold">
            <li><a href="{{ route('home') }}#services" class="block px-2 py-2 transition hover:text-primary">{{ __('services.title') }}</a></li>
            <li><a href="{{ route('home') }}#barberos" class="block px-2 py-2 transition hover:text-primary">{{ __('welcome.barberos.title') }}</a></li>
            <li><a href="{{ route('home') }}#gallery" class="block px-2 py-2 transition hover:text-primary">{{ __('welcome.gallery.title') }}</a></li>
            <li><a href="{{ route('login') }}" class="mt-2 block bg-secondary px-4 py-3 text-center font-bold text-light transition hover:bg-primary">{{ __('common.navigation.login') }}</a></li>
        </ul>
    </div>
</nav>
