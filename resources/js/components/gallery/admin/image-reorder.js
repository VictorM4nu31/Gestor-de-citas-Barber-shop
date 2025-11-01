/**
 * Image Reordering Module
 * Handles drag & drop reordering with visual feedback and touch support
 */

class ImageReorder {
    constructor(options = {}) {
        this.options = {
            container: options.container || '[data-reorder-container]',
            item: options.item || '[data-reorder-item]',
            handle: options.handle || '[data-reorder-handle]',
            saveUrl: options.saveUrl || '/admin/gallery/reorder',
            csrfToken: options.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            ...options
        };

        this.isDragging = false;
        this.draggedElement = null;
        this.placeholder = null;
        this.originalOrder = [];
        this.currentOrder = [];
        this.touchStartPos = { x: 0, y: 0 };
        this.touchThreshold = 10;

        this.init();
    }

    init() {
        this.container = document.querySelector(this.options.container);
        if (!this.container) return;

        this.setupDragAndDrop();
        this.setupTouchEvents();
        this.bindEvents();
        this.saveOriginalOrder();
    }

    setupDragAndDrop() {
        const items = this.container.querySelectorAll(this.options.item);
        
        items.forEach(item => {
            item.draggable = true;
            
            // Add drag handle if specified
            const handle = this.options.handle ? item.querySelector(this.options.handle) : item;
            if (handle) {
                handle.style.cursor = 'grab';
            }

            // Drag events
            item.addEventListener('dragstart', (e) => this.handleDragStart(e));
            item.addEventListener('dragend', (e) => this.handleDragEnd(e));
            item.addEventListener('dragover', (e) => this.handleDragOver(e));
            item.addEventListener('dragenter', (e) => this.handleDragEnter(e));
            item.addEventListener('dragleave', (e) => this.handleDragLeave(e));
            item.addEventListener('drop', (e) => this.handleDrop(e));
        });
    }

    setupTouchEvents() {
        const items = this.container.querySelectorAll(this.options.item);
        
        items.forEach(item => {
            const handle = this.options.handle ? item.querySelector(this.options.handle) : item;
            if (!handle) return;

            handle.addEventListener('touchstart', (e) => this.handleTouchStart(e), { passive: false });
            handle.addEventListener('touchmove', (e) => this.handleTouchMove(e), { passive: false });
            handle.addEventListener('touchend', (e) => this.handleTouchEnd(e), { passive: false });
        });
    }

    bindEvents() {
        // Save button
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-save-order]')) {
                e.preventDefault();
                this.saveOrder();
            }
        });

        // Cancel button
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-cancel-order]')) {
                e.preventDefault();
                this.cancelReorder();
            }
        });

        // Reset button
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-reset-order]')) {
                e.preventDefault();
                this.resetOrder();
            }
        });
    }

    saveOriginalOrder() {
        const items = this.container.querySelectorAll(this.options.item);
        this.originalOrder = Array.from(items).map(item => ({
            id: item.dataset.imageId,
            element: item
        }));
        this.currentOrder = [...this.originalOrder];
    }

    handleDragStart(e) {
        this.isDragging = true;
        this.draggedElement = e.target.closest(this.options.item);
        
        // Set drag image
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.draggedElement.outerHTML);
        
        // Add dragging class
        this.draggedElement.classList.add('dragging');
        
        // Create placeholder
        this.createPlaceholder();
        
        // Update cursor
        const handle = this.options.handle ? this.draggedElement.querySelector(this.options.handle) : this.draggedElement;
        if (handle) {
            handle.style.cursor = 'grabbing';
        }

        // Show reorder controls
        this.showReorderControls();
    }

    handleDragEnd(e) {
        this.isDragging = false;
        
        if (this.draggedElement) {
            this.draggedElement.classList.remove('dragging');
            
            // Update cursor
            const handle = this.options.handle ? this.draggedElement.querySelector(this.options.handle) : this.draggedElement;
            if (handle) {
                handle.style.cursor = 'grab';
            }
        }
        
        // Remove placeholder
        this.removePlaceholder();
        
        // Update current order
        this.updateCurrentOrder();
        
        // Check if order changed
        this.checkOrderChanged();
        
        this.draggedElement = null;
    }

    handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        
        if (!this.isDragging || !this.draggedElement) return;
        
        const afterElement = this.getDragAfterElement(e.clientY);
        
        if (afterElement == null) {
            this.container.appendChild(this.placeholder);
        } else {
            this.container.insertBefore(this.placeholder, afterElement);
        }
    }

    handleDragEnter(e) {
        e.preventDefault();
        const item = e.target.closest(this.options.item);
        if (item && item !== this.draggedElement) {
            item.classList.add('drag-over');
        }
    }

    handleDragLeave(e) {
        const item = e.target.closest(this.options.item);
        if (item) {
            item.classList.remove('drag-over');
        }
    }

    handleDrop(e) {
        e.preventDefault();
        
        const item = e.target.closest(this.options.item);
        if (item) {
            item.classList.remove('drag-over');
        }
        
        if (this.placeholder && this.draggedElement) {
            this.container.insertBefore(this.draggedElement, this.placeholder);
        }
    }

    // Touch event handlers
    handleTouchStart(e) {
        if (e.touches.length !== 1) return;
        
        const touch = e.touches[0];
        this.touchStartPos = { x: touch.clientX, y: touch.clientY };
        this.draggedElement = e.target.closest(this.options.item);
        
        // Prevent scrolling
        e.preventDefault();
    }

    handleTouchMove(e) {
        if (!this.draggedElement || e.touches.length !== 1) return;
        
        const touch = e.touches[0];
        const deltaX = Math.abs(touch.clientX - this.touchStartPos.x);
        const deltaY = Math.abs(touch.clientY - this.touchStartPos.y);
        
        // Check if movement exceeds threshold
        if (deltaX > this.touchThreshold || deltaY > this.touchThreshold) {
            if (!this.isDragging) {
                this.startTouchDrag();
            }
            
            this.updateTouchDrag(touch);
        }
        
        e.preventDefault();
    }

    handleTouchEnd(e) {
        if (this.isDragging) {
            this.endTouchDrag();
        }
        
        this.draggedElement = null;
        this.touchStartPos = { x: 0, y: 0 };
    }

    startTouchDrag() {
        this.isDragging = true;
        this.draggedElement.classList.add('dragging', 'touch-dragging');
        this.createPlaceholder();
        this.showReorderControls();
        
        // Add visual feedback
        this.draggedElement.style.transform = 'scale(1.05)';
        this.draggedElement.style.zIndex = '1000';
        this.draggedElement.style.opacity = '0.9';
    }

    updateTouchDrag(touch) {
        // Move the dragged element
        const rect = this.container.getBoundingClientRect();
        const y = touch.clientY - rect.top;
        
        // Find the element to insert before
        const afterElement = this.getTouchAfterElement(y);
        
        if (afterElement == null) {
            this.container.appendChild(this.placeholder);
        } else {
            this.container.insertBefore(this.placeholder, afterElement);
        }
    }

    endTouchDrag() {
        this.isDragging = false;
        this.draggedElement.classList.remove('dragging', 'touch-dragging');
        
        // Reset styles
        this.draggedElement.style.transform = '';
        this.draggedElement.style.zIndex = '';
        this.draggedElement.style.opacity = '';
        
        // Insert element at placeholder position
        if (this.placeholder) {
            this.container.insertBefore(this.draggedElement, this.placeholder);
        }
        
        this.removePlaceholder();
        this.updateCurrentOrder();
        this.checkOrderChanged();
    }

    getDragAfterElement(y) {
        const draggableElements = [...this.container.querySelectorAll(`${this.options.item}:not(.dragging)`)];
        
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    getTouchAfterElement(y) {
        const draggableElements = [...this.container.querySelectorAll(`${this.options.item}:not(.dragging)`)];
        
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const containerRect = this.container.getBoundingClientRect();
            const relativeY = box.top - containerRect.top + box.height / 2;
            const offset = y - relativeY;
            
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    createPlaceholder() {
        if (this.placeholder) return;
        
        this.placeholder = document.createElement('div');
        this.placeholder.className = 'reorder-placeholder';
        this.placeholder.innerHTML = `
            <div class="border-2 border-dashed border-blue-300 bg-blue-50 rounded-lg p-4 text-center">
                <div class="text-blue-600 text-sm">${__('gallery.admin.reorder.drop_here')}</div>
            </div>
        `;
        
        // Match the height of the dragged element
        if (this.draggedElement) {
            const height = this.draggedElement.offsetHeight;
            this.placeholder.style.height = height + 'px';
        }
    }

    removePlaceholder() {
        if (this.placeholder && this.placeholder.parentNode) {
            this.placeholder.parentNode.removeChild(this.placeholder);
        }
        this.placeholder = null;
    }

    updateCurrentOrder() {
        const items = this.container.querySelectorAll(this.options.item);
        this.currentOrder = Array.from(items).map((item, index) => ({
            id: item.dataset.imageId,
            element: item,
            order: index
        }));
    }

    checkOrderChanged() {
        const hasChanged = this.currentOrder.some((item, index) => {
            return item.id !== this.originalOrder[index]?.id;
        });
        
        if (hasChanged) {
            this.showReorderControls();
        } else {
            this.hideReorderControls();
        }
    }

    showReorderControls() {
        const controls = document.querySelector('[data-reorder-controls]');
        if (controls) {
            controls.classList.remove('hidden');
            controls.classList.add('flex');
        }
    }

    hideReorderControls() {
        const controls = document.querySelector('[data-reorder-controls]');
        if (controls) {
            controls.classList.add('hidden');
            controls.classList.remove('flex');
        }
    }

    async saveOrder() {
        const orderData = this.currentOrder.map((item, index) => ({
            id: item.id,
            display_order: index
        }));

        try {
            this.showSaveLoading();
            
            const response = await fetch(this.options.saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.options.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    images: orderData
                })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                this.handleSaveSuccess();
            } else {
                this.handleSaveError(result.message || 'Error al guardar el orden');
            }
        } catch (error) {
            this.handleSaveError('Error de conexión');
        } finally {
            this.hideSaveLoading();
        }
    }

    cancelReorder() {
        // Restore original order
        this.originalOrder.forEach((item, index) => {
            this.container.appendChild(item.element);
        });
        
        this.currentOrder = [...this.originalOrder];
        this.hideReorderControls();
        
        // Show feedback
        this.showNotification(__('gallery.admin.reorder.changes_cancelled'), 'info');
    }

    resetOrder() {
        // Reset to creation order (by ID)
        const items = Array.from(this.container.querySelectorAll(this.options.item));
        items.sort((a, b) => {
            const idA = parseInt(a.dataset.imageId);
            const idB = parseInt(b.dataset.imageId);
            return idA - idB;
        });
        
        items.forEach(item => {
            this.container.appendChild(item);
        });
        
        this.updateCurrentOrder();
        this.checkOrderChanged();
    }

    showSaveLoading() {
        const saveBtn = document.querySelector('[data-save-order]');
        if (saveBtn) {
            saveBtn.disabled = true;
            saveBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                ${__('gallery.admin.reorder.saving')}
            `;
        }
    }

    hideSaveLoading() {
        const saveBtn = document.querySelector('[data-save-order]');
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.innerHTML = __('gallery.admin.reorder.save_order');
        }
    }

    handleSaveSuccess() {
        // Update original order to current order
        this.originalOrder = [...this.currentOrder];
        this.hideReorderControls();
        
        // Show success notification
        this.showNotification(__('gallery.admin.reorder.order_saved'), 'success');
        
        // Emit custom event
        const event = new CustomEvent('orderSaved', {
            detail: {
                order: this.currentOrder
            }
        });
        document.dispatchEvent(event);
    }

    handleSaveError(message) {
        this.showNotification(message, 'error');
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${this.getNotificationClasses(type)}`;
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    ${this.getNotificationIcon(type)}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button type="button" class="notification-close text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(notification);

        // Close button functionality
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.remove();
        });

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    getNotificationClasses(type) {
        switch (type) {
            case 'success':
                return 'bg-green-50 border border-green-200 text-green-800';
            case 'error':
                return 'bg-red-50 border border-red-200 text-red-800';
            case 'info':
            default:
                return 'bg-blue-50 border border-blue-200 text-blue-800';
        }
    }

    getNotificationIcon(type) {
        switch (type) {
            case 'success':
                return `<svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>`;
            case 'error':
                return `<svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>`;
            case 'info':
            default:
                return `<svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>`;
        }
    }

    // Public API methods
    refresh() {
        this.setupDragAndDrop();
        this.setupTouchEvents();
        this.saveOriginalOrder();
    }

    getOrder() {
        return this.currentOrder.map(item => ({
            id: item.id,
            order: item.order
        }));
    }

    hasChanges() {
        return this.currentOrder.some((item, index) => {
            return item.id !== this.originalOrder[index]?.id;
        });
    }
}

// Export for use in other modules
window.ImageReorder = ImageReorder;

// Auto-initialize if reorder container is present
document.addEventListener('DOMContentLoaded', () => {
    const reorderContainer = document.querySelector('[data-reorder-container]');
    if (reorderContainer) {
        const saveUrl = reorderContainer.dataset.saveUrl || '/admin/gallery/reorder';
        window.galleryImageReorder = new ImageReorder({
            saveUrl: saveUrl
        });
    }
});