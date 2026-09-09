@props([
    'images' => collect(),
    'totalCount' => 0,
    'currentPage' => 1,
    'perPage' => 12,
    'uploadRoute' => '',
    'reorderRoute' => ''
])

<div class="gallery-admin-index" x-data="galleryIndex()" x-on:confirmed-gallery-bulk-delete.window="executeBulkDelete()">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-semibold text-secondary">{{ __('admin.titles.gallery_management') }}</h1>
            <p class="text-metal mt-1">{{ $totalCount }} {{ trans_choice('gallery.admin.management.total_images', $totalCount) }}</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3">
            <a 
                href="{{ route('admin.gallery.create') }}" 
                class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded flex items-center space-x-2 transition-colors"
            >
                <i class="fas fa-plus-circle"></i>
                <span>{{ __('admin.buttons.upload_images') }}</span>
            </a>
            
            <button 
                @click="toggleReorderMode()" 
                :class="reorderMode ? 'bg-warning hover:bg-orange-600' : 'bg-accent hover:bg-gray-600'"
                class="text-light py-2 px-4 rounded flex items-center space-x-2 transition-colors"
            >
                <i class="fas fa-arrows-alt"></i>
                <span x-text="reorderMode ? '{{ __('gallery.admin.management.cancel_order') }}' : '{{ __('gallery.admin.management.reorder_images') }}'"></span>
            </button>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-surface rounded-lg border border-accent p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-secondary mb-2">{{ __('gallery.admin.management.search_images') }}</label>
                <input 
                    type="text" 
                    id="search" 
                    x-model="searchTerm"
                    @input="filterImages()"
                    placeholder="{{ __('gallery.admin.management.search_placeholder') }}"
                    class="w-full px-3 py-2 border border-accent rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                >
            </div>
            
            <div class="md:w-48">
                <label for="status-filter" class="block text-sm font-medium text-secondary mb-2">{{ __('gallery.admin.management.status_filter') }}</label>
                <select 
                    id="status-filter" 
                    x-model="statusFilter"
                    @change="filterImages()"
                    class="w-full px-3 py-2 border border-accent rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                >
                    <option value="">{{ __('gallery.admin.management.all_status') }}</option>
                    <option value="active">{{ __('gallery.admin.management.active_status') }}</option>
                    <option value="inactive">{{ __('gallery.admin.management.inactive_status') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div x-show="selectedImages.length > 0" x-transition class="bg-primary text-light p-4 rounded-lg mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <span x-text="selectedImages.length + ' {{ __('gallery.admin.management.selected_count') }}'"></span>
            
            <div class="flex gap-2">
                <button 
                    @click="bulkToggleActive()"
                    class="bg-accent hover:bg-gray-600 text-light py-1 px-3 rounded text-sm transition-colors"
                >
                    <i class="fas fa-eye"></i>
                    {{ __('gallery.admin.management.change_status') }}
                </button>
                
                <button 
                    @click="bulkDelete()"
                    class="bg-danger hover:bg-red-600 text-light py-1 px-3 rounded text-sm transition-colors"
                >
                    <i class="fas fa-trash"></i>
                    {{ __('admin.buttons.delete') }}
                </button>
                
                <button 
                    @click="clearSelection()"
                    class="bg-metal hover:bg-gray-500 text-light py-1 px-3 rounded text-sm transition-colors"
                >
                    {{ __('admin.buttons.cancel') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Reorder Mode Actions -->
    <div x-show="reorderMode" x-transition class="bg-warning text-dark p-4 rounded-lg mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center space-x-2">
                <i class="fas fa-info-circle"></i>
                <span>{{ __('gallery.admin.management.drag_to_reorder') }}</span>
            </div>
            
            <div class="flex gap-2">
                <button 
                    @click="saveNewOrder()"
                    :disabled="!orderChanged"
                    :class="orderChanged ? 'bg-success hover:bg-green-600' : 'bg-gray-400 cursor-not-allowed'"
                    class="text-light py-2 px-4 rounded transition-colors"
                >
                    <i class="fas fa-save"></i>
                    {{ __('gallery.admin.management.save_order') }}
                </button>
                
                <button 
                    @click="cancelReorder()"
                    class="bg-metal hover:bg-gray-500 text-light py-2 px-4 rounded transition-colors"
                >
                    {{ __('admin.buttons.cancel') }}
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
                <h3 class="text-xl font-medium text-secondary mb-2">{{ __('gallery.no_images') }}</h3>
                <p class="text-metal mb-6">{{ __('gallery.admin.upload.title') }}</p>
                <a 
                    href="{{ route('admin.gallery.create') }}"
                    class="bg-primary hover:bg-secondary text-light py-2 px-6 rounded-lg transition-colors inline-flex items-center"
                >
                    <i class="fas fa-plus-circle mr-2"></i>
                    {{ __('admin.buttons.upload_images') }}
                </a>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($images->hasPages())
        <div class="mt-8">
            {{ $images->links() }}
        </div>
    @endif

    <!-- Edit Modal -->
    <x-admin.gallery.edit-modal />
    <x-ui.confirm-modal id="gallery-bulk-delete" title="Eliminar imágenes seleccionadas" message="Esta acción eliminará las imágenes seleccionadas de forma permanente." />
</div>

@push('scripts')
<script>
// Alpine.js store for gallery state
document.addEventListener('alpine:init', () => {
    Alpine.store('gallery', {
        selectedImages: [],
        
        toggleSelection(imageId) {
            const index = this.selectedImages.indexOf(imageId);
            if (index > -1) {
                this.selectedImages.splice(index, 1);
            } else {
                this.selectedImages.push(imageId);
            }
        },
        
        clearSelection() {
            this.selectedImages = [];
        }
    });
});

function galleryIndex() {
    return {
        searchTerm: '',
        statusFilter: '',
        selectedImages: [],
        reorderMode: false,
        orderChanged: false,
        originalOrder: [],

        init() {
            // Listen for image selection events
            this.$watch('$store.gallery.selectedImages', (selectedImages) => {
                this.selectedImages = selectedImages;
            });
            
            // Listen for custom events
            window.addEventListener('toggle-image-selection', (e) => {
                this.$store.gallery.toggleSelection(e.detail.imageId);
            });
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
            this.$dispatch('open-modal-gallery-bulk-delete', { trigger: this.$root });
        },

        executeBulkDelete() {
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


    }
}
</script>
@endpush
