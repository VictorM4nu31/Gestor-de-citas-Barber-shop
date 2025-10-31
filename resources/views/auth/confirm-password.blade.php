<x-guest-layout>
    <div class="mb-4 text-sm text-muted">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="bg-light">
        @csrf

        <!-- Password -->
        <div>
            <x-shared.input-label for="password" :value="__('Password')" />

            <x-shared.text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-shared.input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-shared.primary-button>
                {{ __('Confirm') }}
            </x-shared.primary-button>
        </div>
    </form>
</x-guest-layout>
