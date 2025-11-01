@props([
    'image',
    'reorderMode' => false,
    'selectable' => true
])

<div 
    class="gallery-image-card bg-surface border border-accent rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow"
    :class="reorderMode ? 'cursor-move' : ''"
    x-data="imageCard({{ $image->id }})"
    data-image-id="{{ $image->id }}"
>
    <!-- Selection Checkbox -->
    @if($selectable)
        <div class="absolute top-2 left-2 z-10" x-show="!reorderMode">
            <input 
                type="checkbox" 
                :checked="isSelected"
                @change="toggleSelection()"
                class="w-4 h-4 text-primary bg-light border-accent rounded focus:ring-primary focus:ring-2"
            >
        </div>
    @endif

    <!-- Reorder Handle -->
    <div x-show="reorderMode" class="absolute top-2 left-2 z-10 bg-warning text-dark p-1 rounded">
        <i class="fas fa-grip-vertical text-sm"></i>
    </div>

    <!-- Status Badge -->
    <div class="absolute top-2 right-2 z-10">
        @if($image->is_active)
            <span class="bg-success text-light px-2 py-1 rounded-full text-xs font-medium">
                <i class="fas fa-eye mr-1"></i>Activa
            </span>
        @else
            <span class="bg-metal text-light px-2 py-1 rounded-full text-xs font-medium">
                <i class="fas fa-eye-slash mr-1"></i>Inactiva
            </span>
        @endif
    </div>

    <!-- Image Container -->
    <div class="relative aspect-square bg-gray-100">
        <img 
            src="{{ $image->thumbnail_url }}" 
            alt="{{ $image->alt_text ?: 'Imagen de galería' }}"
            class="w-full h-full object-cover"
            loading="lazy"
            @click="!reorderMode && openPreview()"
            :class="!reorderMode ? 'cursor-pointer hover:opacity-90 transition-opacity' : ''"
        >
        
        <!-- Image Overlay on Hover -->
        <div 
            class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center"
            x-show="!reorderMode"
        >
            <div class="opacity-0 hover:opacity-100 transition-opacity">
                <button 
                    @click.stop="openPreview()"
                    class="bg-light text-secondary p-2 rounded-full shadow-lg hover:bg-gray-100 transition-colors mr-2"
                    title="Ver imagen completa"
                >
                    <i class="fas fa-search-plus"></i>
                </button>
                <button 
                    @click.stop="openEditModal()"
                    class="bg-primary text-light p-2 rounded-full shadow-lg hover:bg-secondary transition-colors"
                    title="Editar imagen"
                >
                    <i class="fas fa-edit"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Image Info -->
    <div class="p-3">
        <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
                <h4 class="text-sm font-medium text-secondary truncate" title="{{ $image->original_name }}">
                    {{ $image->original_name }}
                </h4>
                
                @if($image->alt_text)
                    <p class="text-xs text-metal mt-1 truncate" title="{{ $image->alt_text }}">
                        {{ $image->alt_text }}
                    </p>
                @endif
                
                <div class="flex items-center justify-between mt-2 text-xs text-metal">
                    <span>{{ number_format($image->size / 1024, 1) }} KB</span>
                    <span>Orden: {{ $image->display_order }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="flex items-center justify-between mt-3 pt-3 border-t border-accent" x-show="!reorderMode">
            <div class="flex space-x-1">
                <button 
                    @click="toggleActive()"
                    :class="imageData.is_active ? 'bg-success hover:bg-green-600' : 'bg-metal hover:bg-gray-500'"
                    class="text-light p-1 rounded text-xs transition-colors"
                    :title="imageData.is_active ? 'Desactivar imagen' : 'Activar imagen'"
                >
                    <i :class="imageData.is_active ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
                
                <button 
                    @click="openEditModal()"
                    class="bg-primary hover:bg-secondary text-light p-1 rounded text-xs transition-colors"
                    title="Editar imagen"
                >
                    <i class="fas fa-edit"></i>
                </button>
                
                <button 
                    @click="deleteImage()"
                    class="bg-danger hover:bg-red-600 text-light p-1 rounded text-xs transition-colors"
                    title="Eliminar imagen"
                >
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            
            <span class="text-xs text-metal">
                {{ $image->created_at->format('d/m/Y') }}
            </span>
        </div>
    </div>
</div>

@push('scripts')
<script>
function imageCard(imageId) {
    return {
        imageId: imageId,
        imageData: @json($image),
        isSelected: false,

        init() {
            // Listen for selection changes from parent
            this.$watch('$store.gallery.selectedImages', (selectedImages) => {
                this.isSelected = selectedImages.includes(this.imageId);
            });
        },

        toggleSelection() {
            this.$dispatch('toggle-image-selection', { imageId: this.imageId });
        },

        openPreview() {
            this.$dispatch('open-image-preview', { 
                imageId: this.imageId,
                imageUrl: this.imageData.image_url,
                altText: this.imageData.alt_text || 'Imagen de galería'
            });
        },

        openEditModal() {
            this.$dispatch('open-edit-modal', { 
                imageId: this.imageId,
                imageData: this.imageData
            });
        },

        toggleActive() {
            fetch(`/admin/gallery/${this.imageId}/toggle-active`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.imageData.is_active = data.is_active;
                    this.$dispatch('image-updated', { imageId: this.imageId, imageData: this.imageData });
                }
            })
            .catch(error => {
                console.error('Error toggling image status:', error);
                alert('Error al cambiar el estado de la imagen');
            });
        },

        deleteImage() {
            if (confirm('¿Estás seguro de que deseas eliminar esta imagen? Esta acción no se puede deshacer.')) {
                fetch(`/admin/gallery/${this.imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.$dispatch('image-deleted', { imageId: this.imageId });
                        // Remove the card from DOM
                        this.$el.remove();
                    } else {
                        alert(data.message || 'Error al eliminar la imagen');
                    }
                })
                .catch(error => {
                    console.error('Error deleting image:', error);
                    alert('Error al eliminar la imagen');
                });
            }
        }
    }
}
</script>
@endpush