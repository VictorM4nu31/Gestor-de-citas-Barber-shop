<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Gestión de Galería</h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.dashboard') }}" class="bg-accent hover:bg-gray-600 text-light py-2 px-4 rounded inline-flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver al Panel</span>
                </a>
            </div>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div id="success-message" class="bg-success text-light p-4 rounded mb-6 flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div id="error-message" class="bg-danger text-light p-4 rounded mb-6 flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Gallery Management Component -->
        <x-admin.gallery.index 
            :images="$images"
            :total-count="$totalCount"
            :current-page="$images->currentPage()"
            :per-page="$images->perPage()"
            :upload-route="route('admin.gallery.store')"
            :reorder-route="route('admin.gallery.reorder')"
        />

        <!-- Reorder Interface (Hidden by default) -->
        <div id="reorder-interface" class="hidden">
            <x-admin.gallery.reorder-interface 
                :images="$images"
                :reorder-route="route('admin.gallery.reorder')"
            />
        </div>
    </main>

    <!-- Image Preview Modal -->
    <div 
        id="image-preview-modal" 
        class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden"
        @click="closePreview()"
    >
        <div class="relative max-w-4xl max-h-[90vh] p-4">
            <button 
                onclick="closePreview()"
                class="absolute top-2 right-2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-colors z-10"
            >
                <i class="fas fa-times"></i>
            </button>
            <img 
                id="preview-image" 
                src="" 
                alt="" 
                class="max-w-full max-h-full object-contain rounded-lg"
            >
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide success/error messages
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');
            
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.opacity = '0';
                    setTimeout(() => successMessage.remove(), 300);
                }, 5000);
            }
            
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.opacity = '0';
                    setTimeout(() => errorMessage.remove(), 300);
                }, 7000);
            }

            // Listen for custom events
            window.addEventListener('open-image-preview', function(e) {
                openImagePreview(e.detail.imageUrl, e.detail.altText);
            });

            window.addEventListener('show-success', function(e) {
                showNotification(e.detail.message, 'success');
            });

            window.addEventListener('show-error', function(e) {
                showNotification(e.detail.message, 'error');
            });

            window.addEventListener('exit-reorder-mode', function() {
                exitReorderMode();
            });
        });

        function openImagePreview(imageUrl, altText) {
            const modal = document.getElementById('image-preview-modal');
            const image = document.getElementById('preview-image');
            
            image.src = imageUrl;
            image.alt = altText;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePreview() {
            const modal = document.getElementById('image-preview-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg text-light z-50 flex items-center space-x-3 ${
                type === 'success' ? 'bg-success' : 'bg-danger'
            }`;
            
            notification.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-2 hover:opacity-75">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        function enterReorderMode() {
            const galleryIndex = document.querySelector('.gallery-admin-index');
            const reorderInterface = document.getElementById('reorder-interface');
            
            if (galleryIndex && reorderInterface) {
                galleryIndex.style.display = 'none';
                reorderInterface.classList.remove('hidden');
            }
        }

        function exitReorderMode() {
            const galleryIndex = document.querySelector('.gallery-admin-index');
            const reorderInterface = document.getElementById('reorder-interface');
            
            if (galleryIndex && reorderInterface) {
                reorderInterface.classList.add('hidden');
                galleryIndex.style.display = 'block';
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // ESC to close modals
            if (e.key === 'Escape') {
                closePreview();
            }
            
            // Ctrl/Cmd + U to open upload modal
            if ((e.ctrlKey || e.metaKey) && e.key === 'u') {
                e.preventDefault();
                window.dispatchEvent(new CustomEvent('open-upload-modal'));
            }
        });
    </script>
    @endpush
</x-app-layout>