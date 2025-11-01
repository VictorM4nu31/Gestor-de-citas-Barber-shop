@props([])

<div 
    id="edit-modal" 
    class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden"
    x-data="editModal()"
    x-show="showModal"
    x-transition
    @open-edit-modal.window="openModal($event.detail)"
>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-light rounded-lg shadow-xl max-w-lg w-full">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-accent">
                <h3 class="text-lg font-semibold text-secondary">{{ __('gallery.admin.modals.edit.title') }}</h3>
                <button 
                    @click="closeModal()"
                    class="text-metal hover:text-secondary transition-colors"
                >
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6" x-show="imageData">
                <form @submit.prevent="saveChanges()">
                    @csrf
                    @method('PUT')
                    
                    <!-- Image Preview -->
                    <div class="mb-6 text-center">
                        <img 
                            :src="imageData?.thumbnail_url" 
                            :alt="imageData?.alt_text || 'Imagen'"
                            class="max-w-full h-32 object-cover rounded-lg mx-auto"
                        >
                    </div>

                    <!-- Alt Text -->
                    <div class="mb-4">
                        <label for="alt_text" class="block text-sm font-medium text-secondary mb-2">
                            {{ __('gallery.admin.modals.edit.alt_text_label') }}
                        </label>
                        <input 
                            type="text" 
                            id="alt_text"
                            x-model="formData.alt_text"
                            placeholder="{{ __('gallery.admin.modals.edit.alt_text_placeholder') }}"
                            class="w-full px-3 py-2 border border-accent rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                        <p class="text-xs text-metal mt-1">
                            {{ __('gallery.admin.modals.edit.alt_text_help') }}
                        </p>
                    </div>

                    <!-- Status -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                x-model="formData.is_active"
                                class="w-4 h-4 text-primary bg-light border-accent rounded focus:ring-primary focus:ring-2"
                            >
                            <span class="ml-2 text-sm text-secondary">{{ __('gallery.admin.modals.edit.active_checkbox') }}</span>
                        </label>
                    </div>

                    <!-- Image Info -->
                    <div class="bg-surface rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-medium text-secondary mb-2">{{ __('gallery.admin.modals.edit.image_info_title') }}</h4>
                        <div class="grid grid-cols-2 gap-4 text-xs text-metal">
                            <div>
                                <span class="font-medium">{{ __('gallery.admin.modals.edit.name_label') }}</span>
                                <p x-text="imageData?.original_name" class="truncate"></p>
                            </div>
                            <div>
                                <span class="font-medium">{{ __('gallery.admin.modals.edit.size_label') }}</span>
                                <p x-text="imageData ? formatFileSize(imageData.size) : ''"></p>
                            </div>
                            <div>
                                <span class="font-medium">{{ __('gallery.admin.modals.edit.order_label') }}</span>
                                <p x-text="imageData?.display_order"></p>
                            </div>
                            <div>
                                <span class="font-medium">{{ __('gallery.admin.modals.edit.uploaded_label') }}</span>
                                <p x-text="imageData ? formatDate(imageData.created_at) : ''"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-accent">
                        <button 
                            type="button"
                            @click="closeModal()"
                            class="px-4 py-2 text-metal hover:text-secondary transition-colors"
                            :disabled="saving"
                        >
                            {{ __('gallery.admin.modals.edit.cancel') }}
                        </button>
                        <button 
                            type="submit"
                            class="bg-primary hover:bg-secondary text-light px-6 py-2 rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="saving"
                        >
                            <span x-show="!saving">
                                <i class="fas fa-save mr-2"></i>
                                {{ __('gallery.admin.modals.edit.save_changes') }}
                            </span>
                            <span x-show="saving">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                {{ __('gallery.admin.modals.edit.saving') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editModal() {
    return {
        showModal: false,
        imageData: null,
        formData: {
            alt_text: '',
            is_active: true
        },
        saving: false,

        openModal(detail) {
            this.imageData = detail.imageData;
            this.formData = {
                alt_text: detail.imageData.alt_text || '',
                is_active: detail.imageData.is_active
            };
            this.showModal = true;
        },

        closeModal() {
            if (!this.saving) {
                this.showModal = false;
                this.imageData = null;
                this.formData = {
                    alt_text: '',
                    is_active: true
                };
            }
        },

        async saveChanges() {
            if (!this.imageData) return;

            this.saving = true;

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
                    this.$dispatch('show-success', { 
                        message: 'Imagen actualizada correctamente' 
                    });
                    this.closeModal();
                    // Reload to show changes
                    setTimeout(() => location.reload(), 1000);
                } else {
                    this.$dispatch('show-error', { 
                        message: data.message || 'Error al actualizar la imagen' 
                    });
                }
            } catch (error) {
                console.error('Update error:', error);
                this.$dispatch('show-error', { 
                    message: 'Error al actualizar la imagen' 
                });
            } finally {
                this.saving = false;
            }
        },

        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        },

        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('es-ES');
        }
    }
}
</script>
@endpush