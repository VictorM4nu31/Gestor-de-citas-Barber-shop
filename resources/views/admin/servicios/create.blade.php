<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Crear Servicio</h2>
            <a href="{{ route('admin.servicios.index') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded-md">Volver a la Lista</a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6 text-secondary">Crear Servicio</h1>
        <form action="{{ route('admin.servicios.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="mb-4">
                    <label for="nombre" class="block text-sm font-medium text-secondary">Nombre</label>
                    <input type="text" id="nombre" name="nombre"
                        class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        required>
                </div>
                <div class="mb-4">
                    <label for="descripcion" class="block text-sm font-medium text-secondary">Descripción</label>
                    <textarea id="descripcion" name="descripcion"
                        class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        required></textarea>
                </div>
                <div class="mb-4">
                    <label for="duracion" class="block text-sm font-medium text-secondary">Duración (minutos)</label>
                    <input type="number" id="duracion" name="duracion" min="1" step="1"
                        class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        required>
                </div>
                <div class="mb-4">
                    <label for="precio" class="block text-sm font-medium text-secondary">Precio</label>
                    <input type="number" id="precio" name="precio" step="0.01"
                        class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        required>
                </div>
                <div class="mb-4">
                    <label for="foto" class="block text-sm font-medium text-secondary">Foto</label>
                    <input type="file" id="foto" name="foto"
                        class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <p class="mt-2 text-sm text-muted">Tamaño máximo: 2MB. Formatos permitidos: jpeg, png, jpg.</p>
                    @if ($errors->has('foto'))
                        <p class="mt-2 text-sm text-danger">{{ $errors->first('foto') }}</p>
                    @endif
                </div>
                <div class="mb-4 flex items-center space-x-2">
                    <input type="checkbox" id="publicado" name="publicado" value="1" checked class="h-4 w-4 text-primary">
                    <label for="publicado" class="text-sm text-secondary">Publicar este servicio</label>
                </div>
                <div class="mb-4">
                    <label for="orden" class="block text-sm font-medium text-secondary">Orden (prioridad)</label>
                    <input type="number" id="orden" name="orden" value="0" min="0"
                        class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <p class="mt-2 text-sm text-muted">Valores más bajos aparecen primero (0 = prioridad normal).</p>
                </div>
            </div>
            <div class="mb-4">
                <button type="submit" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">Guardar</button>
            </div>
        </form>
    </main>
</x-app-layout>