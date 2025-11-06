<section>
    <header>
        <h2 class="text-lg font-medium text-secondary">
            {{ __('profile.Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-muted">
            {{ __('profile.Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-shared.input-label for="update_password_current_password" :value="__('profile.Current Password')" />
            <x-shared.text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-shared.input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-shared.input-label for="update_password_password" :value="__('profile.New Password')" />
            <x-shared.text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-shared.input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-shared.input-label for="update_password_password_confirmation" :value="__('profile.Confirm Password')" />
            <x-shared.text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-shared.input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-shared.primary-button>{{ __('profile.Save') }}</x-shared.primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-muted"
                >{{ __('profile.Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
