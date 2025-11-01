@props([
    'images' => collect(),
    'reorderRoute' => ''
])

<div 
    class="reorder-interface"
    x-data="reorderInterface()"
    x-init="init()"
>
    <!-- Reorder Header -->
    <div class="bg-warning text-dark p-4 rounded-lg mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <i class="fas fa-arrows-alt text-xl"></i>
                <div>
                    <h3 class="font-semibold">Modo de Reordenamiento</h3>
                    <p class="text-sm opacity-90">Arrastra las imágenes para cambiar su orden de visualización</p>
                </div>
            </div>
            
            <div class="flex gap-2">
                <button 
                    @click="saveOrder()"
                    :disabled="!hasChanges"
                    :class="hasChanges ? 'bg-success hover:bg-green-600' : 'bg-gray-400 cursor-not-allowed'"
                    class="text-light py-2 px-4 rounded transition-colors flex items-center space-x-2"
                >
                    <i class="fas fa-save"></i>
                    <span>Guardar Orden</span>
                </button>
                
                <button 
                    @click="cancelReorder()"
                    class="bg-metal hover:bg-gray-500 text-light py-2 px-4 rounded transition-colors flex items-center space-x-2"
                >
                    <i class="fas fa-times"></i>
                    <span>Cancelar</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Reorder Instructions -->
    <div class="bg-surface border border-accent rounded-lg p-4 mb-6">
        <div class="flex items-start space-x-3">
            <i class="fas fa-info-circle text-primary mt-1"></i>
            <div>
                <h4 class="font-medium text-secondary mb-2">Instrucciones de Reordenamiento</h4>
                <ul class="text-sm text-metal space-y-1">
                    <li>• Arrastra las imágenes para cambiar su posición</li>
                    <li>• El orden se refleja de izquierda a derecha, de arriba hacia abajo</li>
                    <li>• Las imágenes con menor número de orden aparecen primero en la galería pública</li>
                    <li>• Los cambios no se guardan hasta que presiones "Guardar Orden"</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Sortable Grid -->
    <div 
        class="sortable-container"
        x-ref="sortableContainer"
    >
        <div 
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4"
            x-ref="sortableGrid"
        >
            @foreach($images as $index => $image)
                <div 
                    class="sortable-item bg-surface border-2 border-accent rounded-lg overflow-hidden shadow-sm cursor-move transition-all duration-200 hover:shadow-md"
                    data-image-id="{{ $image->id }}"
                    data-original-order="{{ $image->display_order }}"
                >
                    <!-- Drag Handle -->
                    <div class="bg-warning text-dark p-2 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-grip-vertical"></i>
                            <span class="text-sm font-medium">Orden: <span class="order-number">{{ $index + 1 }}</span></span>
                        </div>
                        <div class="text-xs opacity-75">
                            ID: {{ $image->id }}
                        </div>
                    </div>

                    <!-- Image Preview -->
                    <div class="relative aspect-square bg-gray-100">
                        <img 
                            src="{{ $image->thumbnail_url }}" 
                            alt="{{ $image->alt_text ?: 'Imagen de galería' }}"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        >
                        
                        <!-- Status Overlay -->
                        <div class="absolute top-2 right-2">
                            @if($image->is_active)
                                <span class="bg-success text-light px-2 py-1 rounded-full text-xs">
                                    <i class="fas fa-eye"></i>
                                </span>
                            @else
                                <span class="bg-metal text-light px-2 py-1 rounded-full text-xs">
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Image Info -->
                    <div class="p-3">
                        <h4 class="text-sm font-medium text-secondary truncate" title="{{ $image->original_name }}">
                            {{ $image->original_name }}
                        </h4>
                        
                        @if($image->alt_text)
                            <p class="text-xs text-metal mt-1 truncate" title="{{ $image->alt_text }}">
                                {{ $image->alt_text }}
                            </p>
                        @endif
                        
                        <div class="flex justify-between items-center mt-2 text-xs text-metal">
                            <span>{{ number_format($image->size / 1024, 1) }} KB</span>
                            <span>Original: {{ $image->display_order }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Changes Summary -->
    <div x-show="hasChanges" x-transition class="mt-6 p-4 bg-primary bg-opacity-10 border border-primary rounded-lg">
        <div class="flex items-start space-x-3">
            <i class="fas fa-exclamation-circle text-primary mt-1"></i>
            <div>
                <h4 class="font-medium text-secondary mb-2">Cambios Pendientes</h4>
                <p class="text-sm text-metal mb-3">
                    Tienes cambios sin guardar en el orden de las imágenes. 
                    Asegúrate de guardar antes de salir del modo de reordenamiento.
                </p>
                <div class="text-xs text-metal">
                    <span x-text="changedItems.length"></span> imagen<span x-text="changedItems.length === 1 ? '' : 'es'"></span> con cambios de orden
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div 
        x-show="isSaving" 
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        x-transition
    >
        <div class="bg-light rounded-lg p-6 flex items-center space-x-4">
            <i class="fas fa-spinner fa-spin text-2xl text-primary"></i>
            <div>
                <h3 class="font-medium text-secondary">Guardando orden...</h3>
                <p class="text-sm text-metal">Por favor espera mientras se actualiza el orden</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
function reorderInterface() {
    return {
        sortable: null,
        originalOrder: [],
        currentOrder: [],
        hasChanges: false,
        changedItems: [],
        isSaving: false,
        reorderRoute: '{{ $reorderRoute }}',

        init() {
            this.initializeSortable();
            this.captureOriginalOrder();
        },

        initializeSortable() {
            const grid = this.$refs.sortableGrid;
            
            this.sortable = Sortable.create(grid, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                handle: '.sortable-item',
                onStart: (evt) => {
                    evt.item.classList.add('dragging');
                },
                onEnd: (evt) => {
                    evt.item.classList.remove('dragging');
                    this.updateOrderNumbers();
                    this.checkForChanges();
                }
            });
        },

        captureOriginalOrder() {
            const items = this.$refs.sortableGrid.querySelectorAll('.sortable-item');
            this.originalOrder = Array.from(items).map(item => ({
                id: parseInt(item.dataset.imageId),
                originalOrder: parseInt(item.dataset.originalOrder)
            }));
            this.currentOrder = [...this.originalOrder];
        },

        updateOrderNumbers() {
            const items = this.$refs.sortableGrid.querySelectorAll('.sortable-item');
            items.forEach((item, index) => {
                const orderSpan = item.querySelector('.order-number');
                if (orderSpan) {
                    orderSpan.textContent = index + 1;
                }
            });
        },

        checkForChanges() {
            const items = this.$refs.sortableGrid.querySelectorAll('.sortable-item');
            this.currentOrder = Array.from(items).map((item, index) => ({
                id: parseInt(item.dataset.imageId),
                newOrder: index + 1,
                originalOrder: parseInt(item.dataset.originalOrder)
            }));

            // Check if any items have changed position
            this.changedItems = this.currentOrder.filter((item, index) => {
                const originalItem = this.originalOrder.find(orig => orig.id === item.id);
                return originalItem && (index + 1) !== originalItem.originalOrder;
            });

            this.hasChanges = this.changedItems.length > 0;
        },

        async saveOrder() {
            if (!this.hasChanges || this.isSaving) return;

            this.isSaving = true;

            try {
                const orderData = this.currentOrder.map((item, index) => ({
                    id: item.id,
                    display_order: index + 1
                }));

                const response = await fetch(this.reorderRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        images: orderData
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Update original order to current order
                    this.originalOrder = [...this.currentOrder];
                    this.hasChanges = false;
                    this.changedItems = [];
                    
                    // Update data attributes
                    const items = this.$refs.sortableGrid.querySelectorAll('.sortable-item');
                    items.forEach((item, index) => {
                        item.dataset.originalOrder = index + 1;
                    });

                    // Show success message
                    this.$dispatch('show-success', { 
                        message: 'Orden de imágenes actualizado correctamente' 
                    });

                    // Exit reorder mode
                    setTimeout(() => {
                        this.$dispatch('exit-reorder-mode');
                    }, 1000);

                } else {
                    throw new Error(data.message || 'Error al guardar el orden');
                }

            } catch (error) {
                console.error('Error saving order:', error);
                this.$dispatch('show-error', { 
                    message: error.message || 'Error al guardar el orden de las imágenes' 
                });
            } finally {
                this.isSaving = false;
            }
        },

        cancelReorder() {
            if (this.hasChanges) {
                if (confirm('¿Estás seguro de que deseas cancelar? Se perderán los cambios no guardados.')) {
                    this.resetOrder();
                    this.$dispatch('exit-reorder-mode');
                }
            } else {
                this.$dispatch('exit-reorder-mode');
            }
        },

        resetOrder() {
            // Reset to original order
            const grid = this.$refs.sortableGrid;
            const items = Array.from(grid.querySelectorAll('.sortable-item'));
            
            // Sort items by original order
            items.sort((a, b) => {
                return parseInt(a.dataset.originalOrder) - parseInt(b.dataset.originalOrder);
            });

            // Re-append items in original order
            items.forEach(item => {
                grid.appendChild(item);
            });

            this.updateOrderNumbers();
            this.hasChanges = false;
            this.changedItems = [];
        },

        destroy() {
            if (this.sortable) {
                this.sortable.destroy();
            }
        }
    }
}
</script>

<style>
.sortable-ghost {
    opacity: 0.4;
    transform: scale(0.95);
}

.sortable-chosen {
    cursor: grabbing !important;
}

.sortable-drag {
    transform: rotate(5deg);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.dragging {
    z-index: 1000;
    transform: rotate(2deg);
}

.sortable-item {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.sortable-item:hover {
    transform: translateY(-2px);
}
</style>
@endpush