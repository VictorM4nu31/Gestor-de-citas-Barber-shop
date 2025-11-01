<x-guest-layout>
    <div class="mb-4 text-sm text-muted">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="bg-light">
        @csrf

        <div>
            <x-shared.input-label for="email" :value="__('Email')" />
            <x-shared.text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-shared.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-shared.primary-button>
                {{ __('Email Password Reset Link') }}
            </x-shared.primary-button>
        </div>
    </form>
</x-guest-layout>
