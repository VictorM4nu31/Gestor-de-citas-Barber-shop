<x-auth-layout>
    <div class="w-full min-h-screen flex items-center justify-center bg-secondary relative overflow-hidden">
        <div class="absolute inset-0 bg-secondary/70"></div>
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary rounded-full filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-secondary rounded-full filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="relative z-10 w-full min-h-screen flex items-center justify-center">
        <div class="bg-light rounded-lg shadow-xl overflow-hidden flex w-full h-screen md:h-auto max-w-6xl mx-auto">
            <div class="w-full md:w-1/2 p-8">
                <h2 class="text-2xl font-bold text-primary mb-2">{{ __('auth.forgot_password') }}</h2>
                <h1 class="text-4xl font-bold text-primary mb-4">{{ __('auth.company_name') }}</h1>
                <p class="text-muted mb-8 text-sm">{{ __('auth.Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</p>

                <x-shared.auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="sr-only">{{ __('auth.Email') }}</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3" aria-hidden="true">
                                <svg class="h-5 w-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </span>
                            <input id="email" type="email" name="email" placeholder="{{ __('auth.Email') }}"
                                class="w-full pl-10 pr-3 py-2 border border-accent rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                                value="{{ old('email') }}" required autofocus autocomplete="username">
                        </div>
                        <x-shared.input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <button type="submit"
                        class="w-full bg-primary text-light py-2 rounded-md hover:bg-secondary transition duration-300">
                        {{ __('auth.Email Password Reset Link') }}
                    </button>
                </form>

                <p class="mt-4 text-sm text-muted">
                    {{ __('auth.remember_password') }}
                    <a href="{{ route('login') }}" class="text-primary hover:underline">{{ __('auth.login_now') }}</a>
                </p>

                <div class="mt-4 pt-4 border-t border-metal">
                    <a href="/" class="flex items-center justify-center text-muted hover:text-primary transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('auth.back_to_home') }}
                    </a>
                </div>
            </div>

            <div class="hidden md:block w-1/2 bg-cover bg-center"
                style="background-image: url('{{ asset('img/login.jpg') }}');">
                <div class="h-full w-full bg-secondary/70 flex items-center justify-center p-8">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold text-light mb-2">{{ __('auth.company_name') }}</h1>
                        <p class="text-light text-sm">{{ __('auth.tagline') }}</p>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</x-auth-layout>
