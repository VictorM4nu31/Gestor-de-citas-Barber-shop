<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Editar Imagen</h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.gallery.show', $galleryImage) }}" class="bg-accent hover:bg-gray-600 text-light py-2 px-4 rounded inline-flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver a Detalles</span>
                </a>
                <a href="{{ route('admin.gallery.index') }}" class="bg-metal hover:bg-gray-500 text-light py-2 px-4 rounded inline-flex items-center space-x-2">
                    <i class="fas fa-images"></i>
                    <span>Galería</span>
                </a>
            </div>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-success text-light p-4 rounded mb-6 flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-danger text-light p-4 rounded mb-6">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-circle mr-3"></i>
                    <span class="font-semibold">Errores encontrados:</span>
                </div>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Edit Form Component -->
        @include('admin.gallery.partials.edit-form', ['galleryImage' => $galleryImage])
    </main>
</x-app-layout>