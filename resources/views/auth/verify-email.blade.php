<x-auth-layout>
    <div class="w-full min-h-screen flex items-center justify-center bg-secondary relative overflow-hidden">
        <div class="absolute inset-0 bg-secondary/70"></div>
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary rounded-full filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-secondary rounded-full filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="relative z-10 w-full min-h-screen flex items-center justify-center">
        <div class="bg-light rounded-lg shadow-xl overflow-hidden flex w-full h-screen md:h-auto max-w-6xl mx-auto">
            <div class="w-full md:w-1/2 p-8">
                <h2 class="text-2xl font-bold text-primary mb-2">{{ __('auth.company_name') }}</h2>
                <h1 class="text-4xl font-bold text-primary mb-4">{{ __('auth.verify_title') }}</h1>
                <p class="text-muted mb-8 text-sm">{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</p>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 font-medium text-sm bg-success text-light p-3 rounded">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif

                <div class="flex flex-col gap-3">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit"
                            class="w-full bg-primary text-light py-2 rounded-md hover:bg-secondary transition duration-300">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-sm text-muted hover:text-secondary underline rounded-md py-2">
                            {{ __('Log Out') }}
                        </button>
                    </form>
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
