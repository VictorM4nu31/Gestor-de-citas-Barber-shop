<nav class="border-b border-secondary/10 bg-background px-4 text-secondary sm:px-8">
    <div class="mx-auto flex min-h-20 max-w-7xl items-center justify-between gap-6">
        <div class="md:order-1">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('img/logo.png') }}" class="h-10 w-10 object-contain" alt="Logo">
                <span class="hidden text-sm font-bold uppercase tracking-[0.18em] sm:block">MasterCut</span>
            </a>
        </div>
        <div class="hidden md:block">
            <ul class="flex items-center gap-8 text-sm font-semibold">
                <li><a class="transition-colors hover:text-primary" href="#services">{{ __('services.title') }}</a></li>
                <li><a class="transition-colors hover:text-primary" href="#barberos">{{ __('welcome.barberos.title') }}</a></li>
                <li><a class="transition-colors hover:text-primary" href="#gallery">{{ __('welcome.gallery.title') }}</a></li>
            </ul>
        </div>
        <div class="flex items-center gap-3">
            {{-- Language Switcher --}}
            <x-shared.language-switcher />

            <a href="{{ route('login') }}" class="hidden border border-secondary px-4 py-2 text-sm font-bold transition hover:bg-secondary hover:text-light sm:inline-flex">
                {{ __('common.navigation.login') }}
            </a>
        </div>
    </div>
</nav>
