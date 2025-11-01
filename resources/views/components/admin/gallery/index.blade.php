@props([
    'images' => collect(),
    'totalCount' => 0,
    'currentPage' => 1,
    'perPage' => 12,
    'uploadRoute' => '',
    'reorderRoute' => ''
])

<div class="gallery-admin-index" x-data="galleryIndex()">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-semibold text-secondary">Gestión de Galería</h1>
            <p class="text-metal mt-1">{{ $totalCount }} {{ $totalCount === 1 ? 'imagen' : 'imágenes' }} en total</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3">
            <button 
                @click="openUploadModal()" 
                class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded flex items-center space-x-2 transition-colors"
            >
                <i class="fas fa-plus-circle"></i>
                <span>Subir Imágenes</span>
            </button>
            
            <button 
                @click="toggleReorderMode()" 
                :class="reorderMode ? 'bg-warning hover:bg-orange-600' : 'bg-accent hover:bg-gray-600'"
                class="text-light py-2 px-4 rounded flex items-center space-x-2 transition-colors"
            >
                <i class="fas fa-arrows-alt"></i>
                <span x-text="reorderMode ? 'Cancelar Orden' : 'Reordenar'"></span>
            </button>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-surface rounded-lg border border-accent p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-secondary mb-2">Buscar imágenes</label>
                <input 
                    type="text" 
                    id="search" 
                    x-model="searchTerm"
                    @input="filterImages()"
                    placeholder="Buscar por nombre o texto alternativo..."
                    class="w-full px-3 py-2 border border-accent rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                >
            </div>
            
            <div class="md:w-48">
                <label for="status-filter" class="block text-sm font-medium text-secondary mb-2">Estado</label>
                <select 
                    id="status-filter" 
                    x-model="statusFilter"
                    @change="filterImages()"
                    class="w-full px-3 py-2 border border-accent rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                >
                    <option value="">Todos</option>
                    <option value="active">Activas</option>
                    <option value="inactive">Inactivas</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div x-show="selectedImages.length > 0" x-transition class="bg-primary text-light p-4 rounded-lg mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <span x-text="`${selectedImages.length} imagen${selectedImages.length === 1 ? '' : 'es'} seleccionada${selectedImages.length === 1 ? '' : 's'}`"></span>
            
            <div class="flex gap-2">
                <button 
                    @click="bulkToggleActive()"
                    class="bg-accent hover:bg-gray-600 text-light py-1 px-3 rounded text-sm transition-colors"
                >
                    <i class="fas fa-eye"></i>
                    Cambiar Estado
                </button>
                
                <button 
                    @click="bulkDelete()"
                    class="bg-danger hover:bg-red-600 text-light py-1 px-3 rounded text-sm transition-colors"
                >
                    <i class="fas fa-trash"></i>
                    Eliminar
                </button>
                
                <button 
                    @click="clearSelection()"
                    class="bg-metal hover:bg-gray-500 text-light py-1 px-3 rounded text-sm transition-colors"
                >
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <!-- Reorder Mode Actions -->
    <div x-show="reorderMode" x-transition class="bg-warning text-dark p-4 rounded-lg mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center space-x-2">
                <i class="fas fa-info-circle"></i>
                <span>Arrastra las imágenes para cambiar su orden de visualización</span>
            </div>
            
            <div class="flex gap-2">
                <button 
                    @click="saveNewOrder()"
                    :disabled="!orderChanged"
                    :class="orderChanged ? 'bg-success hover:bg-green-600' : 'bg-gray-400 cursor-not-allowed'"
                    class="text-light py-2 px-4 rounded transition-colors"
                >
                    <i class="fas fa-save"></i>
                    Guardar Orden
                </button>
                
                <button 
                    @click="cancelReorder()"
                    class="bg-metal hover:bg-gray-500 text-light py-2 px-4 rounded transition-colors"
                >
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <!-- Images Grid -->
    <div class="gallery-grid">
        @if($images->count() > 0)
            <div 
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4"
                :class="reorderMode ? 'sortable-grid' : ''"
                x-ref="imageGrid"
            >
                @foreach($images as $image)
                    <x-admin.gallery.image-card 
                        :image="$image"
                        :reorder-mode="false"
                        :selectable="true"
                    />
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-accent rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-images text-3xl text-light"></i>
                </div>
                <h3 class="text-xl font-medium text-secondary mb-2">No hay imágenes en la galería</h3>
                <p class="text-metal mb-6">Comienza subiendo algunas imágenes para mostrar en tu sitio web</p>
                <button 
                    @click="openUploadModal()"
                    class="bg-primary hover:bg-secondary text-light py-2 px-6 rounded-lg transition-colors"
                >
                    <i class="fas fa-plus-circle mr-2"></i>
                    Subir Primera Imagen
                </button>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($images->hasPages())
        <div class="mt-8">
            {{ $images->links() }}
        </div>
    @endif

    <!-- Upload Modal -->
    <x-admin.gallery.upload-modal :upload-route="$uploadRoute" />
    
    <!-- Edit Modal -->
    <x-admin.gallery.edit-modal />
</div>

@push('scripts')
<script>
function galleryIndex() {
    return {
        searchTerm: '',
        statusFilter: '',
        selectedImages: [],
        reorderMode: false,
        orderChanged: false,
        originalOrder: [],

        init() {
            // Initialize any needed functionality
        },

        filterImages() {
            // This would typically trigger an AJAX request to filter images
            // For now, we'll implement client-side filtering if needed
        },

        toggleSelection(imageId) {
            const index = this.selectedImages.indexOf(imageId);
            if (index > -1) {
                this.selectedImages.splice(index, 1);
            } else {
                this.selectedImages.push(imageId);
            }
        },

        selectAll() {
            // Logic to select all visible images
        },

        clearSelection() {
            this.selectedImages = [];
        },

        bulkToggleActive() {
            if (this.selectedImages.length === 0) return;
            
            // AJAX request to toggle active status
            fetch('/admin/gallery/bulk-toggle-active', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    image_ids: this.selectedImages
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        },

        bulkDelete() {
            if (this.selectedImages.length === 0) return;
            
            if (confirm(`¿Estás seguro de que deseas eliminar ${this.selectedImages.length} imagen${this.selectedImages.length === 1 ? '' : 'es'}?`)) {
                fetch('/admin/gallery/bulk-delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        image_ids: this.selectedImages
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        },

        toggleReorderMode() {
            this.reorderMode = !this.reorderMode;
            if (this.reorderMode) {
                this.initSortable();
            } else {
                this.destroySortable();
            }
        },

        initSortable() {
            // Initialize drag & drop sorting
            // This would use a library like Sortable.js
        },

        destroySortable() {
            // Cleanup sortable functionality
        },

        saveNewOrder() {
            // Save the new order to the server
        },

        cancelReorder() {
            this.reorderMode = false;
            this.orderChanged = false;
            this.destroySortable();
        },

        openUploadModal() {
            this.$dispatch('open-upload-modal');
        }
    }
}
</script>
@endpush