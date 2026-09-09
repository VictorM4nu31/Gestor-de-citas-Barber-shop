<nav x-data="{ open: false }" class="bg-secondary text-white border-b border-accent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <x-layout.logo size="md" />
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(auth()->check() && auth()->user())
                        @php
                            $userHasAdminRole = false;
                            $userHasBarberoRole = false;
                            try {
                                $userHasAdminRole = auth()->user()->hasRole('admin');
                                $userHasBarberoRole = auth()->user()->hasRole('barbero');
                            } catch (\Exception $e) {
                                // Handle role check errors gracefully
                            }
                        @endphp

                        @if($userHasAdminRole)
                            <x-shared.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                                Admin
                            </x-shared.nav-link>
                            <x-shared.nav-link :href="route('admin.barberos.index')" :active="request()->routeIs('admin.barberos.*')">
                                Empleados
                            </x-shared.nav-link>
                            <x-shared.nav-link :href="route('admin.servicios.index')" :active="request()->routeIs('admin.servicios.*')">
                                Servicios
                            </x-shared.nav-link>
                            <x-shared.nav-link :href="route('admin.gallery.index')" :active="request()->routeIs('admin.gallery.*')">
                                Galería
                            </x-shared.nav-link>
                        @elseif($userHasBarberoRole)
                            <x-shared.nav-link :href="route('barbero.dashboard')" :active="request()->routeIs('barbero.*')">
                                Panel Barbero
                            </x-shared.nav-link>
                        @else
                            <x-shared.nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('common.navigation.dashboard') }}
                            </x-shared.nav-link>
                            <x-shared.nav-link :href="route('citas.create')" :active="request()->routeIs('citas.create')">
                                {{ __('common.navigation.schedule') }}
                            </x-shared.nav-link>
                            <x-shared.nav-link :href="route('citas.index')" :active="request()->routeIs('citas.index')">
                                {{ __('common.navigation.my_appointments') }}
                            </x-shared.nav-link>
                        @endif
                    @endif
                    @guest
                        <x-shared.nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('common.navigation.home') }}
                        </x-shared.nav-link>
                        <x-shared.nav-link :href="route('public.servicios.index')" :active="request()->routeIs('public.servicios.*')">
                            {{ __('common.navigation.services') }}
                        </x-shared.nav-link>
                        <x-shared.nav-link :href="route('public.barberos.index')" :active="request()->routeIs('public.barberos.*')">
                            {{ __('common.navigation.barberos') }}
                        </x-shared.nav-link>
                        <x-shared.nav-link :href="route('login')" :active="request()->routeIs('login')">
                            {{ __('common.navigation.login') }}
                        </x-shared.nav-link>
                    @endguest
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                {{-- Language Switcher --}}
                <x-shared.language-switcher />

                @auth
                <x-shared.dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-secondary hover:text-primary focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-shared.dropdown-link :href="route('profile.edit')">
                            {{ __('common.navigation.profile') }}
                        </x-shared.dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-shared.dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('common.navigation.logout') }}
                            </x-shared.dropdown-link>
                        </form>
                    </x-slot>
                </x-shared.dropdown>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-primary hover:bg-secondary/60 focus:outline-none focus:bg-secondary/60 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-secondary text-white">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->check() && auth()->user())
                @php
                    $userHasAdminRole = false;
                    $userHasBarberoRole = false;
                    try {
                        $userHasAdminRole = auth()->user()->hasRole('admin');
                        $userHasBarberoRole = auth()->user()->hasRole('barbero');
                    } catch (\Exception $e) {
                        // Handle role check errors gracefully
                    }
                @endphp

                @if($userHasAdminRole)
                    <x-shared.responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                        Panel Admin
                    </x-shared.responsive-nav-link>
                    <x-shared.responsive-nav-link :href="route('admin.barberos.index')" :active="request()->routeIs('admin.barberos.*')">
                        Empleados
                    </x-shared.responsive-nav-link>
                    <x-shared.responsive-nav-link :href="route('admin.servicios.index')" :active="request()->routeIs('admin.servicios.*')">
                        Servicios
                    </x-shared.responsive-nav-link>
                    <x-shared.responsive-nav-link :href="route('admin.gallery.index')" :active="request()->routeIs('admin.gallery.*')">
                        Galería
                    </x-shared.responsive-nav-link>
                @elseif($userHasBarberoRole)
                    <x-shared.responsive-nav-link :href="route('barbero.dashboard')" :active="request()->routeIs('barbero.*')">
                        Panel Barbero
                    </x-shared.responsive-nav-link>
                @else
                    <x-shared.responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('common.navigation.dashboard') }}
                    </x-shared.responsive-nav-link>
                    <x-shared.responsive-nav-link :href="route('citas.create')" :active="request()->routeIs('citas.create')">
                        {{ __('common.navigation.schedule') }}
                    </x-shared.responsive-nav-link>
                    <x-shared.responsive-nav-link :href="route('citas.index')" :active="request()->routeIs('citas.index')">
                        {{ __('common.navigation.my_appointments') }}
                    </x-shared.responsive-nav-link>
                @endif
            @endif
            @guest
                <x-shared.responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('common.navigation.home') }}
                </x-shared.responsive-nav-link>
                <x-shared.responsive-nav-link :href="route('public.servicios.index')" :active="request()->routeIs('public.servicios.*')">
                    {{ __('common.navigation.services') }}
                </x-shared.responsive-nav-link>
                <x-shared.responsive-nav-link :href="route('public.barberos.index')" :active="request()->routeIs('public.barberos.*')">
                    {{ __('common.navigation.barberos') }}
                </x-shared.responsive-nav-link>
                <x-shared.responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                    {{ __('common.navigation.login') }}
                </x-shared.responsive-nav-link>
            @endguest
        </div>

        {{-- Mobile Language Switcher --}}
        <div class="pt-4 pb-1 border-t border-accent">
            <div class="px-4">
                <div class="font-medium text-base text-white mb-3">{{ __('common.language.switch_to') }}</div>
                <div class="flex space-x-4">
                    @if(app()->getLocale() !== 'es')
                        <a href="{{ route('language.switch', 'es') }}"
                           class="flex items-center space-x-2 px-3 py-2 text-sm text-white hover:text-primary transition-colors duration-150">
                            <span class="text-lg" role="img" aria-label="{{ __('common.language.spanish') }}">🇪🇸</span>
                            <span>{{ __('common.language.spanish') }}</span>
                        </a>
                    @endif
                    @if(app()->getLocale() !== 'en')
                        <a href="{{ route('language.switch', 'en') }}"
                           class="flex items-center space-x-2 px-3 py-2 text-sm text-white hover:text-primary transition-colors duration-150">
                            <span class="text-lg" role="img" aria-label="{{ __('common.language.english') }}">🇺🇸</span>
                            <span>{{ __('common.language.english') }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @auth
        <div class="pt-4 pb-1 border-t border-accent">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-muted">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-shared.responsive-nav-link :href="route('profile.edit')">
                    {{ __('common.navigation.profile') }}
                </x-shared.responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-shared.responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('common.navigation.logout') }}
                    </x-shared.responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>
