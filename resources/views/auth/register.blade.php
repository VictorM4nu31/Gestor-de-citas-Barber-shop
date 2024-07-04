<x-guest-layout>
    <head>
        <!-- Enlazar el CSS -->
        <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    </head>
    <div class="wrapper">
        <div class="flip-card__inner">
            <div class="flip-card__front">
                <div class="title">Sign up</div>
                <form method="POST" action="{{ route('register') }}" class="flip-card__form">
                    @csrf
                    <!-- Name -->
                    <div>
                        <input class="flip-card__input" id="name" type="text" name="name" placeholder="Name" :value="old('name')" required autofocus autocomplete="name">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <input class="flip-card__input" id="email" type="email" name="email" placeholder="Email" :value="old('email')" required autocomplete="username">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <input class="flip-card__input" id="password" type="password" name="password" placeholder="Password" required autocomplete="new-password">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <input class="flip-card__input" id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                            {{ __('Already registered? Log in') }}
                        </a>

                        <button class="flip-card__btn ms-4">
                            {{ __('Register') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

