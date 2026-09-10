@props([
    'images' => collect(),
    'reorderRoute' => ''
])

<div class="reorder-interface" x-data="reorderInterface()">
    <div class="bg-warning text-dark p-4 rounded-lg mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center space-x-2">
                <i class="fas fa-info-circle"></i>
                <span>{{ __('gallery.admin.management.drag_to_reorder') }}</span>
            </div>
            
            <div class="flex gap-2">
                <button 
                    @click="saveOrder()"
                    :disabled="!hasChanges"
                    :class="hasChanges ? 'bg-success hover:bg-green-600' : 'bg-gray-400 cursor-not-allowed'"
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

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4" x-ref="sortableGrid">
        @foreach($images as $image)
            <div class="reorder-item cursor-move" data-image-id="{{ $image->id }}">
                <x-admin.gallery.image-card 
                    :image="$image"
                    :reorder-mode="true"
                    :selectable="false"
                    instance="reorder"
                />
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
function reorderInterface() {
    return {
        hasChanges: false,
        originalOrder: [],
        
        init() {
            this.originalOrder = Array.from(this.$refs.sortableGrid.children).map(el => 
                parseInt(el.dataset.imageId)
            );
            this.initSortable();
        },
        
        initSortable() {
            // Simple drag and drop implementation
            let draggedElement = null;
            
            this.$refs.sortableGrid.addEventListener('dragstart', (e) => {
                draggedElement = e.target.closest('.reorder-item');
                e.dataTransfer.effectAllowed = 'move';
            });
            
            this.$refs.sortableGrid.addEventListener('dragover', (e) => {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
            });
            
            this.$refs.sortableGrid.addEventListener('drop', (e) => {
                e.preventDefault();
                const dropTarget = e.target.closest('.reorder-item');
                
                if (dropTarget && draggedElement && dropTarget !== draggedElement) {
                    const rect = dropTarget.getBoundingClientRect();
                    const midpoint = rect.left + rect.width / 2;
                    
                    if (e.clientX < midpoint) {
                        dropTarget.parentNode.insertBefore(draggedElement, dropTarget);
                    } else {
                        dropTarget.parentNode.insertBefore(draggedElement, dropTarget.nextSibling);
                    }
                    
                    this.checkForChanges();
                }
            });
            
            // Make items draggable
            this.$refs.sortableGrid.querySelectorAll('.reorder-item').forEach(item => {
                item.draggable = true;
            });
        },
        
        checkForChanges() {
            const currentOrder = Array.from(this.$refs.sortableGrid.children).map(el => 
                parseInt(el.dataset.imageId)
            );
            this.hasChanges = !this.arraysEqual(this.originalOrder, currentOrder);
        },
        
        arraysEqual(a, b) {
            return a.length === b.length && a.every((val, i) => val === b[i]);
        },
        
        saveOrder() {
            const newOrder = Array.from(this.$refs.sortableGrid.children).map((el, index) => ({
                id: parseInt(el.dataset.imageId),
                order: index + 1
            }));
            
            fetch('{{ $reorderRoute }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ order: newOrder })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.$dispatch('show-success', { message: '{{ __('gallery.admin.management.order_saved') }}' });
                    this.$dispatch('exit-reorder-mode');
                } else {
                    this.$dispatch('show-error', { message: '{{ __('gallery.admin.management.order_error') }}' });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.$dispatch('show-error', { message: '{{ __('gallery.admin.management.order_error') }}' });
            });
        },
        
        cancelReorder() {
            this.$dispatch('exit-reorder-mode');
        }
    }
}
</script>
@endpush