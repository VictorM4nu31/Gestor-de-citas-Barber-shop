<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-secondary">{{ __('profile.Delete Account') }}</h2>
        <p class="mt-1 text-sm text-muted">{{ __('profile.Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}</p>
    </header>

    <button type="button" @click="$dispatch('open-modal-delete-account', { trigger: $el })" class="inline-flex items-center rounded-sm bg-danger px-4 py-2 text-sm font-semibold text-light transition hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-danger">
        {{ __('profile.Delete Account') }}
    </button>

    <form method="post" action="{{ route('profile.destroy') }}" x-data x-on:confirmed-delete-account.window="$el.submit()">
        @csrf
        @method('delete')

        <x-ui.confirm-modal id="delete-account" title="{{ __('profile.Delete Account') }}" message="{{ __('profile.Are you sure you want to delete your account?') }}" variant="danger">
            <div class="mt-6">
                <label for="delete-account-password" class="sr-only">{{ __('profile.Password') }}</label>
                <input id="delete-account-password" name="password" type="password" class="block w-full border border-accent px-3 py-2 text-sm focus:border-primary focus:ring-primary" placeholder="{{ __('profile.Password') }}" required autocomplete="current-password">
                <x-shared.input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>
        </x-ui.confirm-modal>
    </form>
</section>
