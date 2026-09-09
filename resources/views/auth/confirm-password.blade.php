<x-auth-layout>
    <div class="w-full min-h-screen flex items-center justify-center bg-secondary relative overflow-hidden">
        <div class="absolute inset-0 bg-secondary/70"></div>
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary rounded-full filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-secondary rounded-full filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="relative z-10 w-full min-h-screen flex items-center justify-center">
        <div class="bg-light rounded-lg shadow-xl overflow-hidden flex w-full h-screen md:h-auto max-w-6xl mx-auto">
            <div class="w-full md:w-1/2 p-8">
                <h2 class="text-2xl font-bold text-primary mb-2">{{ __('auth.company_name') }}</h2>
                <h1 class="text-4xl font-bold text-primary mb-4">{{ __('auth.confirm_password_placeholder') }}</h1>
                <p class="text-muted mb-8 text-sm">{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}</p>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="mb-6">
                        <label for="password" class="sr-only">{{ __('Password') }}</label>
                        <input id="password" type="password" name="password" placeholder="{{ __('Password') }}"
                            class="w-full px-3 py-2 border border-accent rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            required autocomplete="current-password">
                        <x-shared.input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <button type="submit"
                        class="w-full bg-primary text-light py-2 rounded-md hover:bg-secondary transition duration-300">
                        {{ __('Confirm') }}
                    </button>
                </form>
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
