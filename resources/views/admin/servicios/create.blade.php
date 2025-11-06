<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">{{ __('admin.titles.create_service') }}</h2>
            <a href="{{ route('admin.servicios.index') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded-md">{{ __('admin.buttons.back_to_list') }}</a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6 text-secondary">{{ __('admin.titles.create_service') }}</h1>
        <form action="{{ route('admin.servicios.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="mb-4">
                    <label for="nombre" class="block text-sm font-medium text-secondary">{{ __('admin.labels.name') }}</label>
                    <input type="text" id="nombre" name="nombre"
                        class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        required>
                </div>
                <div class="mb-4">
                    <label for="descripcion" class="block text-sm font-medium text-secondary">{{ __('admin.labels.description') }}</label>
                    <textarea id="descripcion" name="descripcion"
                        class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        required></textarea>
                </div>
                <div class="mb-4">
                    <label for="duracion" class="block text-sm font-medium text-secondary">{{ __('services.duration') }} ({{ __('services.minutes') }})</label>
                    <input type="number" id="duracion" name="duracion" min="1" step="1"
                        class="mt-1 block w-full border-graymuted rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        required>
                </div>
                <div class="mb-4">
                    <label for="precio" class="block text-sm font-medium text-secondary">{{ __('admin.labels.price') }}</label>
                    <input type="number" id="precio" name="precio" step="0.01"
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
                </div>
                <div class="mb-4 flex items-center space-x-2">
                    <input type="checkbox" id="publicado" name="publicado" value="1" checked class="h-4 w-4 text-primary">
                    <label for="publicado" class="text-sm text-secondary">{{ __('admin.labels.published') }}</label>
                </div>
                <div class="mb-4">
                    <label for="orden" class="block text-sm font-medium text-secondary">{{ __('admin.labels.order') }}</label>
                    <input type="number" id="orden" name="orden" value="0" min="0"
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
