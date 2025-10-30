<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Barbero</h2>
            <a href="{{ route('barberos.index') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">Volver a la lista</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('barberos.update', $barbero->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="mb-4">
                            <label for="nombre_completo" class="block text-sm font-medium text-secondary">Nombre Completo</label>
                            <input type="text" id="nombre_completo" name="nombre_completo" value="{{ $barbero->nombre_completo }}" class="mt-1 block w-full border border-graymuted rounded-md shadow-sm focus:border-primary focus:ring-primary" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-secondary">Email</label>
                            <input type="email" id="email" name="email" value="{{ $barbero->email }}" class="mt-1 block w-full border border-graymuted rounded-md shadow-sm focus:border-primary focus:ring-primary" required>
                        </div>
                        <!-- Campo de contraseña -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-secondary">Contraseña (dejar en blanco para mantener la actual)</label>
                            <input type="password" id="password" name="password" class="mt-1 block w-full border border-graymuted rounded-md shadow-sm focus:border-primary focus:ring-primary">
                            @if ($errors->has('password'))
                                <span class="text-danger text-sm">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <!-- Campo de confirmación de contraseña -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-secondary">Confirmar Contraseña</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full border border-graymuted rounded-md shadow-sm focus:border-primary focus:ring-primary">
                            @if ($errors->has('password_confirmation'))
                                <span class="text-danger text-sm">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>
                        <div class="mb-4">
                            <label for="telefono" class="block text-sm font-medium text-secondary">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" value="{{ $barbero->telefono }}" class="mt-1 block w-full border border-graymuted rounded-md shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div class="mb-4">
                            <label for="especialidad" class="block text-sm font-medium text-secondary">Especialidad</label>
                            <input type="text" id="especialidad" name="especialidad" value="{{ $barbero->especialidad }}" class="mt-1 block w-full border border-graymuted rounded-md shadow-sm focus:border-primary focus:ring-primary" required>
                        </div>
                        <div class="mb-4">
                            <label for="experiencia" class="block text-sm font-medium text-secondary">Experiencia</label>
                            <textarea id="experiencia" name="experiencia" class="mt-1 block w-full border border-graymuted rounded-md shadow-sm focus:border-primary focus:ring-primary" required>{{ $barbero->experiencia }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="foto" class="block text-sm font-medium text-secondary">Foto</label>
                            <input type="file" id="foto" name="foto" class="mt-1 block w-full border border-graymuted rounded-md shadow-sm focus:border-primary focus:ring-primary">
                            <p class="mt-2 text-sm text-muted">Tamaño máximo: 2MB. Formatos permitidos: jpeg, png, jpg.</p>
                            <!-- Mostrar foto -->
                            @if ($barbero->foto)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $barbero->foto) }}" alt="Foto de {{ $barbero->nombre_completo }}" class="w-32 h-32 object-cover rounded-md border border-metal">
                                </div>
                            @endif
                            <!-- Mensaje de error para la foto -->
                            @if ($errors->has('foto'))
                                <p class="mt-2 text-sm text-danger">{{ $errors->first('foto') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
