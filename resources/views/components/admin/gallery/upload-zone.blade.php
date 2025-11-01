@props([
    'uploadRoute' => '',
    'maxFiles' => 10,
    'maxSize' => 5120, // KB
    'acceptedTypes' => 'image/jpeg,image/png,image/webp'
])

<div 
    class="upload-zone"
    x-data="uploadZone()"
    x-init="init()"
>
    <!-- Upload Area -->
    <div 
        class="border-2 border-dashed border-accent rounded-lg p-8 text-center transition-all duration-200"
        :class="{
            'border-primary bg-primary bg-opacity-5': isDragOver,
            'border-danger bg-danger bg-opacity-5': hasError,
            'border-success bg-success bg-opacity-5': isUploading && progress > 0
        }"
        @dragover.prevent="handleDragOver"
        @dragleave.prevent="handleDragLeave"
        @drop.prevent="handleDrop"
        @click="$refs.fileInput.click()"
    >
        <!-- Upload Icon and Text -->
        <div class="mb-4">
            <div class="mx-auto w-16 h-16 bg-accent rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-cloud-upload-alt text-2xl text-light" x-show="!isUploading"></i>
                <i class="fas fa-spinner fa-spin text-2xl text-light" x-show="isUploading"></i>
            </div>
            
            <h3 class="text-lg font-medium text-secondary mb-2">
                <span x-show="!isUploading">Arrastra imágenes aquí o haz clic para seleccionar</span>
                <span x-show="isUploading">Subiendo imágenes...</span>
            </h3>
            
            <p class="text-metal text-sm" x-show="!isUploading">
                Formatos soportados: JPG, PNG, WEBP<br>
                Tamaño máximo: {{ number_format($maxSize / 1024, 1) }}MB por imagen<br>
                Máximo {{ $maxFiles }} imágenes por vez
            </p>
        </div>

        <!-- Progress Bar -->
        <div x-show="isUploading" class="w-full bg-gray-200 rounded-full h-2 mb-4">
            <div 
                class="bg-primary h-2 rounded-full transition-all duration-300"
                :style="`width: ${progress}%`"
            ></div>
        </div>

        <!-- Upload Status -->
        <div x-show="isUploading" class="text-sm text-metal">
            <span x-text="`${uploadedCount} de ${totalFiles} archivos subidos`"></span>
        </div>

        <!-- Hidden File Input -->
        <input 
            type="file" 
            x-ref="fileInput"
            @change="handleFileSelect"
            multiple 
            accept="{{ $acceptedTypes }}"
            class="hidden"
        >
    </div>

    <!-- File Preview List -->
    <div x-show="selectedFiles.length > 0" class="mt-6">
        <h4 class="text-lg font-medium text-secondary mb-4">
            Archivos seleccionados (<span x-text="selectedFiles.length"></span>)
        </h4>
        
        <div class="space-y-3 max-h-64 overflow-y-auto">
            <template x-for="(file, index) in selectedFiles" :key="index">
                <div class="flex items-center justify-between p-3 bg-surface border border-accent rounded-lg">
                    <!-- File Info -->
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                        <!-- Preview Thumbnail -->
                        <div class="w-12 h-12 bg-gray-100 rounded overflow-hidden flex-shrink-0">
                            <img 
                                :src="file.preview" 
                                :alt="file.name"
                                class="w-full h-full object-cover"
                                x-show="file.preview"
                            >
                            <div x-show="!file.preview" class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-image text-metal"></i>
                            </div>
                        </div>
                        
                        <!-- File Details -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-secondary truncate" x-text="file.name"></p>
                            <p class="text-xs text-metal">
                                <span x-text="formatFileSize(file.size)"></span>
                                <span x-show="file.error" class="text-danger ml-2" x-text="file.error"></span>
                            </p>
                        </div>
                    </div>

                    <!-- File Status -->
                    <div class="flex items-center space-x-2 flex-shrink-0">
                        <!-- Upload Progress -->
                        <div x-show="file.uploading" class="w-8 h-8 flex items-center justify-center">
                            <i class="fas fa-spinner fa-spin text-primary"></i>
                        </div>
                        
                        <!-- Success -->
                        <div x-show="file.uploaded" class="w-8 h-8 flex items-center justify-center">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                        
                        <!-- Error -->
                        <div x-show="file.error" class="w-8 h-8 flex items-center justify-center">
                            <i class="fas fa-exclamation-circle text-danger"></i>
                        </div>
                        
                        <!-- Remove Button -->
                        <button 
                            @click="removeFile(index)"
                            x-show="!file.uploading && !file.uploaded"
                            class="w-8 h-8 flex items-center justify-center text-metal hover:text-danger transition-colors"
                            title="Eliminar archivo"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Alt Text Input Section -->
        <div class="mt-6 p-4 bg-surface border border-accent rounded-lg">
            <h5 class="text-md font-medium text-secondary mb-3">Texto alternativo (opcional)</h5>
            <p class="text-sm text-metal mb-4">Agrega texto alternativo para mejorar la accesibilidad de las imágenes</p>
            
            <template x-for="(file, index) in selectedFiles" :key="index">
                <div x-show="!file.error" class="mb-3">
                    <label :for="`alt-text-${index}`" class="block text-sm font-medium text-secondary mb-1" x-text="file.name"></label>
                    <input 
                        :id="`alt-text-${index}`"
                        type="text" 
                        x-model="file.altText"
                        :placeholder="`Descripción de ${file.name}`"
                        class="w-full px-3 py-2 border border-accent rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                        maxlength="255"
                    >
                </div>
            </template>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center mt-6">
            <button 
                @click="clearFiles()"
                x-show="!isUploading"
                class="bg-metal hover:bg-gray-500 text-light py-2 px-4 rounded transition-colors"
            >
                <i class="fas fa-times mr-2"></i>
                Limpiar Todo
            </button>
            
            <div class="flex space-x-3">
                <button 
                    @click="$dispatch('close-upload-modal')"
                    x-show="!isUploading"
                    class="bg-accent hover:bg-gray-600 text-light py-2 px-4 rounded transition-colors"
                >
                    Cancelar
                </button>
                
                <button 
                    @click="uploadFiles()"
                    x-show="!isUploading && hasValidFiles()"
                    class="bg-primary hover:bg-secondary text-light py-2 px-6 rounded transition-colors"
                >
                    <i class="fas fa-upload mr-2"></i>
                    Subir <span x-text="validFilesCount()"></span> Imagen<span x-text="validFilesCount() === 1 ? '' : 'es'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    <div x-show="errorMessage" class="mt-4 p-4 bg-danger bg-opacity-10 border border-danger rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
            <span class="text-danger text-sm" x-text="errorMessage"></span>
        </div>
    </div>
</div>

@push('scripts')
<script>
function uploadZone() {
    return {
        selectedFiles: [],
        isDragOver: false,
        isUploading: false,
        hasError: false,
        errorMessage: '',
        progress: 0,
        uploadedCount: 0,
        totalFiles: 0,
        maxFiles: {{ $maxFiles }},
        maxSize: {{ $maxSize * 1024 }}, // Convert to bytes
        acceptedTypes: ['image/jpeg', 'image/png', 'image/webp'],
        uploadRoute: '{{ $uploadRoute }}',

        init() {
            // Initialize component
        },

        handleDragOver(e) {
            this.isDragOver = true;
        },

        handleDragLeave(e) {
            this.isDragOver = false;
        },

        handleDrop(e) {
            this.isDragOver = false;
            const files = Array.from(e.dataTransfer.files);
            this.processFiles(files);
        },

        handleFileSelect(e) {
            const files = Array.from(e.target.files);
            this.processFiles(files);
        },

        processFiles(files) {
            this.errorMessage = '';
            this.hasError = false;

            // Check file count limit
            if (files.length > this.maxFiles) {
                this.showError(`Solo puedes subir máximo ${this.maxFiles} archivos a la vez`);
                return;
            }

            // Process each file
            files.forEach(file => {
                const fileData = {
                    file: file,
                    name: file.name,
                    size: file.size,
                    type: file.type,
                    altText: '',
                    preview: null,
                    error: null,
                    uploading: false,
                    uploaded: false
                };

                // Validate file
                const validation = this.validateFile(file);
                if (!validation.valid) {
                    fileData.error = validation.error;
                    this.hasError = true;
                }

                // Generate preview for valid images
                if (validation.valid && file.type.startsWith('image/')) {
                    this.generatePreview(file, fileData);
                }

                this.selectedFiles.push(fileData);
            });
        },

        validateFile(file) {
            // Check file type
            if (!this.acceptedTypes.includes(file.type)) {
                return {
                    valid: false,
                    error: 'Tipo de archivo no soportado'
                };
            }

            // Check file size
            if (file.size > this.maxSize) {
                return {
                    valid: false,
                    error: `Archivo muy grande (máx. ${this.formatFileSize(this.maxSize)})`
                };
            }

            return { valid: true };
        },

        generatePreview(file, fileData) {
            const reader = new FileReader();
            reader.onload = (e) => {
                fileData.preview = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        removeFile(index) {
            this.selectedFiles.splice(index, 1);
            if (this.selectedFiles.length === 0) {
                this.hasError = false;
                this.errorMessage = '';
            }
        },

        clearFiles() {
            this.selectedFiles = [];
            this.hasError = false;
            this.errorMessage = '';
            this.$refs.fileInput.value = '';
        },

        hasValidFiles() {
            return this.selectedFiles.some(file => !file.error);
        },

        validFilesCount() {
            return this.selectedFiles.filter(file => !file.error).length;
        },

        async uploadFiles() {
            if (!this.hasValidFiles()) return;

            this.isUploading = true;
            this.progress = 0;
            this.uploadedCount = 0;
            this.totalFiles = this.validFilesCount();

            const validFiles = this.selectedFiles.filter(file => !file.error);

            for (let i = 0; i < validFiles.length; i++) {
                const fileData = validFiles[i];
                fileData.uploading = true;

                try {
                    await this.uploadSingleFile(fileData);
                    fileData.uploaded = true;
                    fileData.uploading = false;
                    this.uploadedCount++;
                    this.progress = Math.round((this.uploadedCount / this.totalFiles) * 100);
                } catch (error) {
                    fileData.error = error.message || 'Error al subir archivo';
                    fileData.uploading = false;
                    this.hasError = true;
                }
            }

            this.isUploading = false;

            // If all files uploaded successfully, close modal and refresh
            if (this.uploadedCount === this.totalFiles) {
                setTimeout(() => {
                    this.$dispatch('upload-completed');
                    this.clearFiles();
                }, 1000);
            }
        },

        uploadSingleFile(fileData) {
            return new Promise((resolve, reject) => {
                const formData = new FormData();
                formData.append('images[]', fileData.file);
                if (fileData.altText) {
                    formData.append('alt_texts[]', fileData.altText);
                }

                fetch(this.uploadRoute, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        resolve(data);
                    } else {
                        reject(new Error(data.message || 'Error al subir archivo'));
                    }
                })
                .catch(error => {
                    reject(error);
                });
            });
        },

        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        },

        showError(message) {
            this.errorMessage = message;
            this.hasError = true;
        }
    }
}
</script>
@endpush