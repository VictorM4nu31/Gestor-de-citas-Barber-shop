<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap">
            <h2 class="font-semibold text-xl text-white leading-tight">Panel de Administración</h2>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Barberos Card -->
            <div class="bg-surface rounded-lg shadow-md p-6 border border-accent">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-secondary mb-2">Barberos</h3>
                        <p class="text-2xl font-bold text-primary">{{ $barberos->count() }}</p>
                        <p class="text-sm text-gray-600">Total registrados</p>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-users text-3xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.barberos.index') }}" class="text-primary hover:text-secondary text-sm font-medium">
                        Ver todos →
                    </a>
                </div>
            </div>

            <!-- Servicios Card -->
            <div class="bg-surface rounded-lg shadow-md p-6 border border-accent">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-secondary mb-2">Servicios</h3>
                        <p class="text-2xl font-bold text-primary">{{ $servicios->count() }}</p>
                        <p class="text-sm text-gray-600">Total disponibles</p>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-cut text-3xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.servicios.index') }}" class="text-primary hover:text-secondary text-sm font-medium">
                        Ver todos →
                    </a>
                </div>
            </div>

            <!-- Galería Card -->
            <div class="bg-surface rounded-lg shadow-md p-6 border border-accent">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-secondary mb-2">Galería</h3>
                        <p class="text-2xl font-bold text-primary">{{ $galleryImages->count() }}</p>
                        <p class="text-sm text-gray-600">Imágenes activas</p>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-images text-3xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.gallery.index') }}" class="text-primary hover:text-secondary text-sm font-medium">
                        Gestionar →
                    </a>
                </div>
            </div>

            <!-- Citas Card -->
            <div class="bg-surface rounded-lg shadow-md p-6 border border-accent">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-secondary mb-2">Citas</h3>
                        <p class="text-2xl font-bold text-primary">{{ $citasHoy ?? 0 }}</p>
                        <p class="text-sm text-gray-600">Para hoy</p>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-calendar-alt text-3xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.citas.index') }}" class="text-primary hover:text-secondary text-sm font-medium">
                        Ver todas →
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-surface rounded-lg shadow-md p-6 border border-accent">
            <h3 class="text-lg font-semibold text-secondary mb-4">Acciones Rápidas</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.barberos.create') }}" class="bg-primary hover:bg-secondary text-white py-3 px-4 rounded-lg text-center transition-colors">
                    <i class="fas fa-user-plus mb-2 block text-xl"></i>
                    Nuevo Barbero
                </a>
                <a href="{{ route('admin.servicios.create') }}" class="bg-primary hover:bg-secondary text-white py-3 px-4 rounded-lg text-center transition-colors">
                    <i class="fas fa-plus mb-2 block text-xl"></i>
                    Nuevo Servicio
                </a>
                <a href="{{ route('admin.gallery.create') }}" class="bg-primary hover:bg-secondary text-white py-3 px-4 rounded-lg text-center transition-colors">
                    <i class="fas fa-image mb-2 block text-xl"></i>
                    Subir Imágenes
                </a>
                <a href="{{ route('admin.citas.create') }}" class="bg-primary hover:bg-secondary text-white py-3 px-4 rounded-lg text-center transition-colors">
                    <i class="fas fa-calendar-plus mb-2 block text-xl"></i>
                    Nueva Cita
                </a>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dashboard functionality can be added here
        });
    </script>
    @endpush
</x-app-layout>
