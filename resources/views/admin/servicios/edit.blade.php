<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">{{ __('admin.titles.edit_service') }}</h2>
            <a href="{{ route('admin.servicios.index') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded-md">{{ __('admin.buttons.back_to_list') }}</a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6 text-secondary">{{ __('admin.titles.edit_service') }}</h1>
        <form action="{{ route('admin.servicios.update', $servicio->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="mb-4">
                    <label for="nombre" class="block text-sm font-medium text-secondary">{{ __('admin.labels.name') }}</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $servicio->nombre) }}"
                        class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        required>
                </div>
                <div class="mb-4">
                    <label for="descripcion" class="block text-sm font-medium text-secondary">{{ __('admin.labels.description') }}</label>
                    <textarea id="descripcion" name="descripcion"
                        class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        required>{{ old('descripcion', $servicio->descripcion) }}</textarea>
                </div>
                <div class="mb-4">
                    <label for="duracion" class="block text-sm font-medium text-secondary">{{ __('services.duration') }} ({{ __('services.minutes') }})</label>
                    <input type="number" id="duracion" name="duracion" min="1" step="1"
                        value="{{ old('duracion', $servicio->duracion) }}"
                        class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        required>
                </div>
                <div class="mb-4">
                    <label for="precio" class="block text-sm font-medium text-secondary">{{ __('admin.labels.price') }}</label>
                    <input type="number" id="precio" name="precio" step="0.01"
                        value="{{ old('precio', $servicio->precio) }}"
                        class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        required>
                </div>
                <div class="mb-4">
                    <label for="foto" class="block text-sm font-medium text-secondary">{{ __('admin.labels.photo') }}</label>
                    <input type="file" id="foto" name="foto"
                        class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <p class="mt-2 text-sm text-muted">{{ __('admin.labels.max_size') }}</p>
                    @if ($errors->has('foto'))
                        <p class="mt-2 text-sm text-danger">{{ $errors->first('foto') }}</p>
                    @endif
                    @if ($servicio->foto)
                        <div class="mt-4">
                            <img src="{{ asset('storage/' . $servicio->foto) }}" alt="Foto de {{ $servicio->nombre }}"
                                class="w-32 h-32 object-cover rounded">
                            <p class="text-sm text-muted">{{ __('admin.labels.current_photo') }}</p>
                        </div>
                    @endif
                </div>
                <div class="mb-4 flex items-center space-x-2">
                    <input type="checkbox" id="publicado" name="publicado" value="1" {{ old('publicado', $servicio->publicado) ? 'checked' : '' }} class="h-4 w-4 text-primary">
                    <label for="publicado" class="text-sm text-secondary">{{ __('admin.labels.published') }}</label>
                </div>
                <div class="mb-4">
                    <label for="orden" class="block text-sm font-medium text-secondary">{{ __('admin.labels.order') }}</label>
                    <input type="number" id="orden" name="orden" value="{{ old('orden', $servicio->orden ?? 0) }}" min="0"
                        class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <p class="mt-2 text-sm text-muted">{{ __('admin.labels.order_help') }}</p>
                </div>
            </div>
            <div class="mb-4">
                <button type="submit" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">{{ __('admin.buttons.save') }}</button>
            </div>
        </form>
    </main>
</x-app-layout>
