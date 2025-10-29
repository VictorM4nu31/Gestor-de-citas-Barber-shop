<x-auth-layout>
    <div class="w-full min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-red-900 to-gray-900 relative overflow-hidden">
        <!-- Patrón de fondo animado -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 2px, rgba(220, 38, 38, 0.1) 2px, rgba(220, 38, 38, 0.1) 4px);"></div>
        </div>
        <!-- Círculos decorativos -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-red-600 rounded-full filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-gray-800 rounded-full filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="relative z-10 w-full min-h-screen flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden flex w-full h-screen md:h-auto max-w-6xl mx-auto">
            <!-- Lado izquierdo: Formulario de inicio de sesión -->
            <div class="w-full md:w-1/2 p-8">
                <h2 class="text-2xl font-bold text-red-600 mb-2">BIENVENIDO A</h2>
                <h1 class="text-4xl font-bold text-red-600 mb-4">MASTER CUT BARBER SHOP</h1>
                <p class="text-gray-600 mb-8 text-sm">Inicia sesión para obtener actualizaciones al momento sobre las
                    cosas que te interesan.</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </span>
                            <input id="email" type="email" name="email" placeholder="Correo Electronico"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-600"
                                :value="old('email')" required autofocus autocomplete="username">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </span>
                            <input id="password" type="password" name="password" placeholder="Contraseña"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-600"
                                required autocomplete="current-password">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <button type="submit"
                        class="w-full bg-red-600 text-white py-2 rounded-md hover:bg-red-700 transition duration-300">
                        INICIAR SESIÓN
                    </button>
                </form>

                <p class="mt-4 text-sm text-gray-600">
                    ¿No tienes una cuenta?
                    <a href="{{ route('register') }}" class="text-red-600 hover:underline">Regístrate ahora</a>
                </p>
            </div>

            <!-- Lado derecho: Imagen de fondo -->
            <div class="hidden md:block w-1/2 bg-cover bg-center"
                style="background-image: url('{{ asset('img/login.jpg') }}');">
                <div class="h-full w-full bg-red-600 bg-opacity-75 flex items-center justify-center p-8">
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
    