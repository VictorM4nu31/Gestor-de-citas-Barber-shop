<x-auth-layout>
    <div class="w-full min-h-screen flex items-center justify-center bg-secondary relative overflow-hidden">
        <div class="absolute inset-0 bg-secondary/70"></div>
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary rounded-full filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-secondary rounded-full filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="relative z-10 w-full min-h-screen flex items-center justify-center">
        <div class="bg-light rounded-lg shadow-xl overflow-hidden flex w-full h-screen md:h-auto max-w-6xl mx-auto">
            <!-- Lado izquierdo: Formulario de registro -->
            <div class="w-full md:w-1/2 p-8">
                <h2 class="text-2xl font-bold text-primary mb-2">BIENVENIDO A</h2>
                <h1 class="text-4xl font-bold text-primary mb-4">MASTER CUT BARBER SHOP</h1>
                <p class="text-muted mb-8 text-sm">Regístrate para obtener actualizaciones al momento sobre las cosas que te interesan.</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <!-- Nombre -->
                    <div class="mb-4">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </span>
                            <input id="name" type="text" name="name" placeholder="Nombre"
                                   class="w-full pl-10 pr-3 py-2 border border-graymuted rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                                   :value="old('name')" required autofocus autocomplete="name">
                        </div>
                        <x-shared.input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Correo electrónico -->
                    <div class="mb-4">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </span>
                            <input id="email" type="email" name="email" placeholder="Correo electrónico"
                                   class="w-full pl-10 pr-3 py-2 border border-graymuted rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                                   :value="old('email')" required autocomplete="username">
                        </div>
                        <x-shared.input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-4">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <input id="password" type="password" name="password" placeholder="Contraseña"
                                   class="w-full pl-10 pr-3 py-2 border border-graymuted rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                                   required autocomplete="new-password">
                        </div>
                        <x-shared.input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="mb-6">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirmar Contraseña"
                                   class="w-full pl-10 pr-3 py-2 border border-graymuted rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                                   required autocomplete="new-password">
                        </div>
                        <x-shared.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full bg-primary text-light py-2 rounded-md hover:bg-secondary transition duration-300">
                        REGISTRARSE
                    </button>
                </form>

                <p class="mt-4 text-sm text-muted">
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('login') }}" class="text-primary hover:underline">Inicia sesión</a>
                </p>
                
                <div class="mt-4 pt-4 border-t border-metal">
                    <a href="/" class="flex items-center justify-center text-muted hover:text-primary transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver a la página principal
                    </a>
                </div>
            </div>

            <!-- Lado derecho: Imagen de fondo -->
            <div class="hidden md:block w-1/2 bg-cover bg-center" style="background-image: url('{{ asset('img/login.jpg') }}');">
                <div class="h-full w-full bg-primary bg-opacity-75 flex items-center justify-center p-8">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold text-white mb-2">MASTER CUT BARBER SHOP</h1>
                        <p class="text-white text-sm">Experimenta el mejor estilo para tu cabello.</p>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</x-auth-layout>
