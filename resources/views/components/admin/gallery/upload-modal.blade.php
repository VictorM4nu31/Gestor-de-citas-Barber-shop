@props([
    'uploadRoute' => ''
])

<div 
    id="upload-modal" 
    class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden"
    x-data="uploadModal()"
    x-show="showModal"
    x-transition
    @open-upload-modal.window="showModal = true"
>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-light rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-accent">
                <h3 class="text-lg font-semibold text-secondary">{{ __('gallery.admin.modals.upload.title') }}</h3>
                <button 
                    @click="closeModal()"
                    class="text-metal hover:text-secondary transition-colors"
                >
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form @submit.prevent="uploadImages()" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- File Upload Area -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-secondary mb-2">
                            {{ __('gallery.admin.modals.upload.select_images') }}
                        </label>
                        <div 
                            class="border-2 border-dashed border-accent rounded-lg p-8 text-center hover:border-primary transition-colors"
                            @dragover.prevent
                            @drop.prevent="handleDrop($event)"
                        >
                            <div class="mb-4">
                                <i class="fas fa-cloud-upload-alt text-4xl text-accent"></i>
                            </div>
                            <p class="text-metal mb-2">
                                {{ __('gallery.admin.modals.upload.drag_drop_hint') }} 
                                <button type="button" @click="$refs.fileInput.click()" class="text-primary hover:underline">
                                    {{ __('gallery.admin.modals.upload.click_to_select') }}
                                </button>
                            </p>
                            <p class="text-xs text-metal">
                                {{ __('gallery.admin.modals.upload.supported_formats') }}
                            </p>
                            
                            <input 
                                type="file" 
                                x-ref="fileInput"
                                @change="handleFileSelect($event)"
                                multiple 
                                accept="image/*"
                                class="hidden"
                            >
                        </div>
                    </div>

                    <!-- Selected Files Preview -->
                    <div x-show="selectedFiles.length > 0" class="mb-6">
                        <h4 class="text-sm font-medium text-secondary mb-3">
                            {{ __('gallery.admin.modals.upload.selected_images') }} (<span x-text="selectedFiles.length"></span>)
                        </h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 max-h-60 overflow-y-auto">
                            <template x-for="(file, index) in selectedFiles" :key="index">
                                <div class="relative bg-surface rounded-lg overflow-hidden">
                                    <img 
                                        :src="file.preview" 
                                        :alt="file.name"
                                        class="w-full h-20 object-cover"
                                    >
                                    <button 
                                        type="button"
                                        @click="removeFile(index)"
                                        class="absolute top-1 right-1 bg-danger text-light rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-700 transition-colors"
                                    >
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <div class="p-2">
                                        <p class="text-xs text-secondary truncate" :title="file.name" x-text="file.name"></p>
                                        <p class="text-xs text-metal" x-text="formatFileSize(file.size)"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Upload Progress -->
                    <div x-show="uploading" class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-secondary">{{ __('gallery.admin.modals.upload.uploading') }}</span>
                            <span class="text-sm text-metal" x-text="`${uploadProgress}%`"></span>
                        </div>
                        <div class="w-full bg-surface rounded-full h-2">
                            <div 
                                class="bg-primary h-2 rounded-full transition-all duration-300"
                                :style="`width: ${uploadProgress}%`"
                            ></div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-accent">
                        <button 
                            type="button"
                            @click="closeModal()"
                            class="px-4 py-2 text-metal hover:text-secondary transition-colors"
                            :disabled="uploading"
                        >
                            {{ __('gallery.admin.modals.upload.cancel') }}
                        </button>
                        <button 
                            type="submit"
                            class="bg-primary hover:bg-secondary text-light px-6 py-2 rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="selectedFiles.length === 0 || uploading"
                        >
                            <span x-show="!uploading">
                                <i class="fas fa-upload mr-2"></i>
                                {{ __('gallery.admin.modals.upload.upload_button') }}
                            </span>
                            <span x-show="uploading">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                {{ __('gallery.admin.modals.upload.uploading_button') }}
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
function uploadModal() {
    return {
        showModal: false,
        selectedFiles: [],
        uploading: false,
        uploadProgress: 0,

        closeModal() {
            if (!this.uploading) {
                this.showModal = false;
                this.selectedFiles = [];
                this.uploadProgress = 0;
            }
        },

        handleFileSelect(event) {
            this.processFiles(event.target.files);
        },

        handleDrop(event) {
            this.processFiles(event.dataTransfer.files);
        },

        processFiles(files) {
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/') && file.size <= 10 * 1024 * 1024) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.selectedFiles.push({
                            file: file,
                            name: file.name,
                            size: file.size,
                            preview: e.target.result
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
        },

        removeFile(index) {
            this.selectedFiles.splice(index, 1);
        },

        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        },

        async uploadImages() {
            if (this.selectedFiles.length === 0) return;

            this.uploading = true;
            this.uploadProgress = 0;

            const formData = new FormData();
            this.selectedFiles.forEach((fileObj, index) => {
                formData.append(`images[${index}]`, fileObj.file);
            });

            try {
                const response = await fetch('{{ $uploadRoute }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    this.$dispatch('show-success', { 
                        message: `${data.uploaded_count} imagen${data.uploaded_count === 1 ? '' : 'es'} subida${data.uploaded_count === 1 ? '' : 's'} correctamente` 
                    });
                    this.closeModal();
                    // Reload the page to show new images
                    setTimeout(() => location.reload(), 1000);
                } else {
                    this.$dispatch('show-error', { 
                        message: data.message || 'Error al subir las imágenes' 
                    });
                }
            } catch (error) {
                console.error('Upload error:', error);
                this.$dispatch('show-error', { 
                    message: 'Error al subir las imágenes' 
                });
            } finally {
                this.uploading = false;
                this.uploadProgress = 0;
            }
        }
    }
}
</script>
@endpush