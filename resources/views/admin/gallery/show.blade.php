<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Detalles de la Imagen</h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.gallery.index') }}" class="bg-accent hover:bg-gray-600 text-light py-2 px-4 rounded inline-flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver a la Galería</span>
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

        @if (session('error'))
            <div class="bg-danger text-light p-4 rounded mb-6 flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Image Details Component -->
        @include('admin.gallery.partials.image-details', ['galleryImage' => $galleryImage])
    </main>
</x-app-layout>