<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Crear Barbero</h2>
            <a href="{{ route('admin.barberos.index') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">Volver a la Lista</a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6 text-secondary">Crear Barbero</h1>
        <form action="{{ route('admin.barberos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="nombre_completo" class="block text-sm font-medium text-secondary">Nombre Completo</label>
                <input type="text" id="nombre_completo" name="nombre_completo" class="mt-1 block w-full border border-graymuted rounded-md shadow-sm focus:border-primary focus:ring-primary" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-secondary">Email</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full border border-graymuted rounded-md shadow-sm focus:border-primary focus:ring-primary" required>
            </div>
            <!-- Campo de contraseña -->
            <div>
                <label for="password" class="block text-sm font-medium text-secondary">Contraseña</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full border border-graymuted rounded-md shadow-sm focus:border-primary focus:ring-primary" required>
                @if ($errors->has('password'))
                    <span class="text-danger text-sm">{{ $errors->first('password') }}</span>
                @endif
            </div>
            <!-- Campo de confirmación de contraseña -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-secondary">Confirmar Contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full border border-graymuted rounded-md shadow-sm focus:border-primary focus:ring-primary" required>
                @if ($errors->has('password_confirmation'))
                    <span class="text-danger text-sm">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </div>
            <div class="mb-4">
                <label for="telefono" class="block text-sm font-medium text-secondary">Teléfono</label>
                <input type="text" id="telefono" name="telefono" class="mt-1 block w-full border border-graymuted rounded-md shadow-sm focus:border-primary focus:ring-primary">
            </div>
            <div class="mb-4">
                <label for="especialidad" class="block text-sm font-medium text-secondary">Especialidad</label>
                <input type="text" id="especialidad" name="especialidad" class="mt-1 block w-full border border-graymuted rounded-md shadow-sm focus:border-primary focus:ring-primary" required>
            </div>
            <div class="mb-4">
                <label for="experiencia" class="block text-sm font-medium text-secondary">Experiencia</label>
                <textarea id="experiencia" name="experiencia" class="mt-1 block w-full border border-graymuted rounded-md shadow-sm focus:border-primary focus:ring-primary" required></textarea>
            </div>
            <div class="mb-4">
                <label for="foto" class="block text-sm font-medium text-secondary">Foto</label>
                <input type="file" id="foto" name="foto" class="mt-1 block w-full border border-graymuted rounded-md shadow-sm focus:border-primary focus:ring-primary">
                <p class="mt-2 text-sm text-muted">Tamaño máximo: 2MB. Formatos permitidos: jpeg, png, jpg.</p>
                <!-- Mensaje de error para la foto -->
                @if ($errors->has('foto'))
                    <p class="mt-2 text-sm text-danger">{{ $errors->first('foto') }}</p>
                @endif
            </div>
            
            <!-- Sección de servicios -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-secondary mb-3">Servicios que ofrece</label>
                <div class="space-y-3 max-h-64 overflow-y-auto border border-graymuted rounded-md p-4">
                    @if($servicios->count() > 0)
                        @foreach($servicios as $servicio)
                            <div class="flex items-start space-x-3 p-3 border border-gray-200 rounded-md hover:bg-gray-50">
                                <input type="checkbox" 
                                       name="servicios[]" 
                                       value="{{ $servicio->id }}"
                                       id="servicio_{{ $servicio->id }}"
                                       class="mt-1 h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <div class="flex-1">
                                    <label for="servicio_{{ $servicio->id }}" class="block text-sm font-medium text-gray-900 cursor-pointer">
                                        {{ $servicio->nombre }}
                                    </label>
                                    <div class="text-sm text-gray-600 mt-1">
                                        <span class="font-medium">${{ number_format($servicio->precio, 0, ',', '.') }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $servicio->duracion }} min</span>
                                    </div>
                                    @if($servicio->descripcion)
                                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($servicio->descripcion, 80) }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-500 italic">No hay servicios publicados disponibles.</p>
                    @endif
                </div>
                @if ($errors->has('servicios'))
                    <p class="mt-2 text-sm text-danger">{{ $errors->first('servicios') }}</p>
                @endif
                <p class="mt-2 text-xs text-gray-500">Selecciona los servicios que este barbero puede ofrecer. Solo se mostrarán servicios publicados.</p>
            </div>
            <div class="mb-4">
                <button type="submit" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">Guardar</button>
            </div>
        </form>
    </main>
</x-app-layout>