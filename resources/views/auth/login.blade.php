<x-guest-layout>
    <head>
        <!-- Enlazar el CSS -->
        <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    </head>
    <div class="wrapper">
        <div class="flip-card__inner">
            <div class="flip-card__front">
                <div class="title">Log in</div>
                <form method="POST" action="{{ route('login') }}" class="flip-card__form">
                    @csrf
                    <!-- Email Address -->
                    <div>
                        <input class="flip-card__input" id="email" type="email" name="email" placeholder="Email" :value="old('email')" required autofocus autocomplete="username">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <input class="flip-card__input" id="password" type="password" name="password" placeholder="Contraseña" required autocomplete="current-password">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('register') }}">
                            {{ __('Don\'t have an account? Register') }}
                        </a>

                        <button class="flip-card__btn ms-4">
                            {{ __('Log in') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
