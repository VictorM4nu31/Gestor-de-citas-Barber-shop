<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Editar Barbero</h2>
            <a href="{{ route('admin.dashboard') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded-md">Volver a la Lista</a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6 text-secondary">Editar Barbero</h1>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-secondary text-light">
            <form action="{{ route('barberos.update', $barbero->id) }}" method="POST" enctype="multipart/form-data"
                class="p-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="mb-4">
                        <label for="nombre_completo" class="block text-sm font-medium text-light">Nombre
                            Completo</label>
                        <input type="text" id="nombre_completo" name="nombre_completo"
                            value="{{ $barbero->nombre_completo }}"
                            class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-secondary bg-light"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-light">Email</label>
                        <input type="email" id="email" name="email" value="{{ $barbero->email }}"
                            class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-secondary bg-light"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-light mb-2">Contraseña</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" 
                                   placeholder="Deja vacío para mantener la contraseña actual"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-black pr-10">
                            <button type="button" id="togglePassword" 
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-graylight hover:text-light">
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242"></path>
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1 text-sm text-graylight">Solo ingresa una nueva contraseña si deseas cambiarla</p>
                    </div>
                    <div class="mb-4">
                        <label for="telefono" class="block text-sm font-medium text-light">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" value="{{ $barbero->telefono }}"
                            class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-secondary bg-light">
                    </div>
                    <div class="mb-4">
                        <label for="especialidad" class="block text-sm font-medium text-light">Especialidad</label>
                        <input type="text" id="especialidad" name="especialidad" value="{{ $barbero->especialidad }}"
                            class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-secondary bg-light"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="experiencia" class="block text-sm font-medium text-light">Experiencia</label>
                        <textarea id="experiencia" name="experiencia"
                            class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-secondary bg-light"
                            required>{{ $barbero->experiencia }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label for="foto" class="block text-sm font-medium text-light">Foto</label>
                        <input type="file" id="foto" name="foto"
                            class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-secondary bg-light">
                        @if ($barbero->foto)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $barbero->foto) }}"
                                    alt="Foto de {{ $barbero->nombre_completo }}"
                                    class="w-32 h-32 object-cover rounded-md">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="mb-4">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </main>

    @push('scripts')
    <script>
        // Toggle para mostrar/ocultar contraseña
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        });
    </script>
    @endpush

</x-app-layout>
