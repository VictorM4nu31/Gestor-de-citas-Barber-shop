@props([
    'updateRoute' => ''
])

<div 
    class="edit-modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    x-data="editModal()"
    x-show="isOpen"
    x-transition
    @open-edit-modal.window="openModal($event.detail)"
    @close-edit-modal.window="closeModal()"
    @keydown.escape.window="closeModal()"
>
    <!-- Modal Container -->
    <div 
        class="bg-light rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden"
        @click.stop
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
    >
        <!-- Modal Header -->
        <div class="bg-secondary text-light p-6 border-b border-accent">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold">Editar Imagen</h2>
                    <p class="text-gray-300 mt-1">Modifica la información de la imagen</p>
                </div>
                <button 
                    @click="closeModal()"
                    class="text-gray-300 hover:text-light transition-colors p-2"
                    title="Cerrar modal"
                >
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-200px)]">
            <form @submit.prevent="saveChanges()" x-show="imageData">
                <!-- Image Preview -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-secondary mb-3">Vista Previa</label>
                    <div class="flex items-start space-x-4">
                        <!-- Thumbnail -->
                        <div class="flex-shrink-0">
                            <img 
                                :src="imageData?.thumbnail_url" 
                                :alt="imageData?.alt_text || 'Vista previa'"
                                class="w-32 h-32 object-cover rounded-lg border border-accent"
                            >
                        </div>
                        
                        <!-- Image Info -->
                        <div class="flex-1 space-y-2">
                            <div>
                                <span class="text-sm font-medium text-secondary">Nombre original:</span>
                                <span class="text-sm text-metal ml-2" x-text="imageData?.original_name"></span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-secondary">Tamaño:</span>
                                <span class="text-sm text-metal ml-2" x-text="formatFileSize(imageData?.size)"></span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-secondary">Tipo:</span>
                                <span class="text-sm text-metal ml-2" x-text="imageData?.mime_type"></span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-secondary">Subida:</span>
                                <span class="text-sm text-metal ml-2" x-text="formatDate(imageData?.created_at)"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alt Text Field -->
                <div class="mb-6">
                    <label for="edit-alt-text" class="block text-sm font-medium text-secondary mb-2">
                        Texto Alternativo
                        <span class="text-metal font-normal">(para accesibilidad)</span>
                    </label>
                    <textarea 
                        id="edit-alt-text"
                        x-model="formData.alt_text"
                        rows="3"
                        maxlength="255"
                        placeholder="Describe brevemente el contenido de la imagen..."
                        class="w-full px-3 py-2 border border-accent rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                    ></textarea>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-metal">
                            Ayuda a usuarios con discapacidades visuales a entender el contenido
                        </p>
                        <span class="text-xs text-metal" x-text="`${formData.alt_text?.length || 0}/255`"></span>
                    </div>
                </div>

                <!-- Display Order Field -->
                <div class="mb-6">
                    <label for="edit-display-order" class="block text-sm font-medium text-secondary mb-2">
                        Orden de Visualización
                    </label>
                    <div class="flex items-center space-x-3">
                        <input 
                            type="number"
                            id="edit-display-order"
                            x-model.number="formData.display_order"
                            min="0"
                            max="9999"
                            class="w-24 px-3 py-2 border border-accent rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                        <span class="text-sm text-metal">
                            Números menores aparecen primero (0 = primera posición)
                        </span>
                    </div>
                </div>

                <!-- Active Status -->
                <div class="mb-6">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input 
                            type="checkbox"
                            x-model="formData.is_active"
                            class="w-4 h-4 text-primary bg-light border-accent rounded focus:ring-primary focus:ring-2"
                        >
                        <div>
                            <span class="text-sm font-medium text-secondary">Imagen activa</span>
                            <p class="text-xs text-metal">Solo las imágenes activas se muestran en la galería pública</p>
                        </div>
                    </label>
                </div>

                <!-- Change Summary -->
                <div x-show="hasChanges()" class="mb-6 p-4 bg-warning bg-opacity-10 border border-warning rounded-lg">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-exclamation-triangle text-warning mt-1"></i>
                        <div>
                            <h4 class="font-medium text-secondary mb-2">Cambios Pendientes</h4>
                            <ul class="text-sm text-metal space-y-1">
                                <li x-show="formData.alt_text !== imageData?.alt_text">
                                    • Texto alternativo modificado
                                </li>
                                <li x-show="formData.display_order !== imageData?.display_order">
                                    • Orden de visualización cambiado
                                </li>
                                <li x-show="formData.is_active !== imageData?.is_active">
                                    • Estado de activación modificado
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Error Messages -->
                <div x-show="errorMessage" class="mb-6 p-4 bg-danger bg-opacity-10 border border-danger rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-exclamation-circle text-danger"></i>
                        <span class="text-danger text-sm" x-text="errorMessage"></span>
                    </div>
                </div>

                <!-- Success Messages -->
                <div x-show="successMessage" class="mb-6 p-4 bg-success bg-opacity-10 border border-success rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-check-circle text-success"></i>
                        <span class="text-success text-sm" x-text="successMessage"></span>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-surface border-t border-accent p-6">
            <div class="flex justify-between items-center">
                <button 
                    @click="resetForm()"
                    x-show="hasChanges()"
                    class="bg-metal hover:bg-gray-500 text-light py-2 px-4 rounded transition-colors"
                >
                    <i class="fas fa-undo mr-2"></i>
                    Deshacer Cambios
                </button>
                
                <div class="flex space-x-3" :class="!hasChanges() ? 'ml-auto' : ''">
                    <button 
                        @click="closeModal()"
                        class="bg-accent hover:bg-gray-600 text-light py-2 px-4 rounded transition-colors"
                    >
                        Cancelar
                    </button>
                    
                    <button 
                        @click="saveChanges()"
                        :disabled="!hasChanges() || isSaving"
                        :class="hasChanges() && !isSaving ? 'bg-primary hover:bg-secondary' : 'bg-gray-400 cursor-not-allowed'"
                        class="text-light py-2 px-6 rounded transition-colors flex items-center space-x-2"
                    >
                        <i :class="isSaving ? 'fas fa-spinner fa-spin' : 'fas fa-save'"></i>
                        <span x-text="isSaving ? 'Guardando...' : 'Guardar Cambios'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editModal() {
    return {
        isOpen: false,
        imageData: null,
        formData: {
            alt_text: '',
            display_order: 0,
            is_active: true
        },
        originalFormData: {},
        isSaving: false,
        errorMessage: '',
        successMessage: '',

        openModal(detail) {
            this.imageData = detail.imageData;
            this.resetForm();
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.isOpen = false;
            this.imageData = null;
            this.errorMessage = '';
            this.successMessage = '';
            document.body.style.overflow = '';
        },

        resetForm() {
            if (this.imageData) {
                this.formData = {
                    alt_text: this.imageData.alt_text || '',
                    display_order: this.imageData.display_order || 0,
                    is_active: this.imageData.is_active || false
                };
                this.originalFormData = { ...this.formData };
            }
        },

        hasChanges() {
            if (!this.imageData) return false;
            
            return this.formData.alt_text !== this.originalFormData.alt_text ||
                   this.formData.display_order !== this.originalFormData.display_order ||
                   this.formData.is_active !== this.originalFormData.is_active;
        },

        async saveChanges() {
            if (!this.hasChanges() || this.isSaving || !this.imageData) return;

            this.isSaving = true;
            this.errorMessage = '';
            this.successMessage = '';

            try {
                const response = await fetch(`/admin/gallery/${this.imageData.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.formData)
                });

                const data = await response.json();

                if (data.success) {
                    // Update the original data
                    this.imageData = { ...this.imageData, ...this.formData };
                    this.originalFormData = { ...this.formData };
                    
                    this.successMessage = 'Imagen actualizada correctamente';
                    
                    // Dispatch event to update the image in the parent component
                    this.$dispatch('image-updated', { 
                        imageId: this.imageData.id, 
                        imageData: this.imageData 
                    });

                    // Close modal after a short delay
                    setTimeout(() => {
                        this.closeModal();
                    }, 1500);

                } else {
                    this.errorMessage = data.message || 'Error al actualizar la imagen';
                }

            } catch (error) {
                console.error('Error updating image:', error);
                this.errorMessage = 'Error de conexión al actualizar la imagen';
            } finally {
                this.isSaving = false;
            }
        },

        formatFileSize(bytes) {
            if (!bytes) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        },

        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
}
</script>
@endpush