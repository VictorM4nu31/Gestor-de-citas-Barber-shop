<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Crear Barbero</h2>
            <x-ui.button href="{{ route('admin.barberos.index') }}">
                Volver a la Lista
            </x-ui.button>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6 text-secondary">Crear Barbero</h1>
        <form action="{{ route('admin.barberos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <x-form.input 
                name="nombre_completo" 
                type="text" 
                label="Nombre Completo" 
                required 
            />
            
            <x-form.input 
                name="email" 
                type="email" 
                label="Email" 
                required 
            />
            
            <x-form.input 
                name="password" 
                type="password" 
                label="Contraseña" 
                required 
            />
            
            <x-form.input 
                name="password_confirmation" 
                type="password" 
                label="Confirmar Contraseña" 
                required 
            />
            
            <x-form.input 
                name="telefono" 
                type="text" 
                label="Teléfono" 
            />
            
            <x-form.input 
                name="especialidad" 
                type="text" 
                label="Especialidad" 
                required 
            />
            
            <x-form.textarea 
                name="experiencia" 
                label="Experiencia" 
                required 
            />
            
            <div class="mb-4">
                <x-form.label for="foto">Foto</x-form.label>
                <input type="file" id="foto" name="foto" class="w-full px-3 py-2 border border-accent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200">
                <p class="mt-2 text-sm text-muted">Tamaño máximo: 2MB. Formatos permitidos: jpeg, png, jpg.</p>
                <x-form.error field="foto" />
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
                <x-ui.button type="submit">
                    Guardar
                </x-ui.button>
            </div>
        </form>
    </main>
</x-app-layout>