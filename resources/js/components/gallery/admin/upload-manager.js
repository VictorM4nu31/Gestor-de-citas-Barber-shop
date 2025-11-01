/**
 * Admin Upload Manager Module
 * Handles file upload with progress tracking, drag & drop, and validation
 */

class UploadManager {
    constructor(options = {}) {
        this.options = {
            uploadUrl: options.uploadUrl || '/admin/gallery',
            maxFileSize: options.maxFileSize || 5 * 1024 * 1024, // 5MB
            maxFiles: options.maxFiles || 10,
            allowedTypes: options.allowedTypes || ['image/jpeg', 'image/png', 'image/webp'],
            csrfToken: options.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            ...options
        };

        this.uploadQueue = [];
        this.activeUploads = 0;
        this.maxConcurrentUploads = 3;
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.setupDropZones();
        this.setupNetworkMonitoring();
    }

    bindEvents() {
        // File input change event
        document.addEventListener('change', (e) => {
            if (e.target.matches('[data-upload-input]')) {
                this.handleFileSelect(e.target.files);
            }
        });

        // Upload button clicks
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-upload-trigger]')) {
                e.preventDefault();
                const input = document.querySelector('[data-upload-input]');
                if (input) input.click();
            }
        });

        // Retry upload buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-retry-upload]')) {
                e.preventDefault();
                const fileId = e.target.dataset.fileId;
                this.retryUpload(fileId);
            }
        });

        // Cancel upload buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-cancel-upload]')) {
                e.preventDefault();
                const fileId = e.target.dataset.fileId;
                this.cancelUpload(fileId);
            }
        });
    }

    setupDropZones() {
        const dropZones = document.querySelectorAll('[data-drop-zone]');
        
        dropZones.forEach(zone => {
            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                zone.addEventListener(eventName, this.preventDefaults, false);
            });

            // Highlight drop zone when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                zone.addEventListener(eventName, () => this.highlight(zone), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                zone.addEventListener(eventName, () => this.unhighlight(zone), false);
            });

            // Handle dropped files
            zone.addEventListener('drop', (e) => this.handleDrop(e), false);
        });
    }

    setupNetworkMonitoring() {
        // Monitor network status
        window.addEventListener('online', () => {
            // Resume failed uploads when connection is restored
            const failedItems = this.uploadQueue.filter(item => 
                item.status === 'error' && item.error.includes('conexión')
            );
            
            if (failedItems.length > 0) {
                setTimeout(() => {
                    this.retryAllFailed();
                }, 1000); // Wait a second before retrying
            }
        });

        window.addEventListener('offline', () => {
            // Pause active uploads when connection is lost
            this.pauseUploads();
        });
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    highlight(zone) {
        zone.classList.add('drag-over');
    }

    unhighlight(zone) {
        zone.classList.remove('drag-over');
    }

    handleDrop(e) {
        const files = e.dataTransfer.files;
        this.handleFileSelect(files);
    }

    handleFileSelect(files) {
        const fileArray = Array.from(files);
        const validFiles = this.validateFiles(fileArray);
        
        if (validFiles.length > 0) {
            this.addFilesToQueue(validFiles);
            this.processQueue();
        }
    }

    validateFiles(files) {
        const validFiles = [];
        const errors = [];

        // Check total number of files
        if (files.length > this.options.maxFiles) {
            errors.push(`Máximo ${this.options.maxFiles} archivos permitidos`);
            files = files.slice(0, this.options.maxFiles);
        }

        files.forEach((file) => {
            const validation = this.validateSingleFile(file);
            if (validation.valid) {
                validFiles.push(file);
            } else {
                errors.push(`${file.name}: ${validation.error}`);
            }
        });

        if (errors.length > 0) {
            this.showErrors(errors);
        }

        return validFiles;
    }

    validateSingleFile(file) {
        // Check file type
        if (!this.options.allowedTypes.includes(file.type)) {
            return {
                valid: false,
                error: 'Tipo de archivo no permitido. Use JPG, PNG o WEBP.'
            };
        }

        // Check file size
        if (file.size > this.options.maxFileSize) {
            const maxSizeMB = this.options.maxFileSize / (1024 * 1024);
            return {
                valid: false,
                error: `Archivo muy grande. Máximo ${maxSizeMB}MB permitido.`
            };
        }

        return { valid: true };
    }

    addFilesToQueue(files) {
        files.forEach(file => {
            const fileId = this.generateFileId();
            const queueItem = {
                id: fileId,
                file: file,
                status: 'pending',
                progress: 0,
                xhr: null,
                retryCount: 0,
                maxRetries: 3
            };

            this.uploadQueue.push(queueItem);
            this.renderFilePreview(queueItem);
        });
    }

    generateFileId() {
        return 'file_' + Date.now() + '_' + Math.random().toString(36).substring(2, 11);
    }

    processQueue() {
        while (this.activeUploads < this.maxConcurrentUploads && this.uploadQueue.length > 0) {
            const nextFile = this.uploadQueue.find(item => item.status === 'pending');
            if (nextFile) {
                this.uploadFile(nextFile);
            } else {
                break;
            }
        }
    }

    uploadFile(queueItem) {
        queueItem.status = 'uploading';
        this.activeUploads++;
        this.updateFileStatus(queueItem);

        const formData = new FormData();
        formData.append('images[]', queueItem.file);
        formData.append('_token', this.options.csrfToken);

        const xhr = new XMLHttpRequest();
        queueItem.xhr = xhr;

        // Upload progress
        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                queueItem.progress = Math.round(percentComplete);
                this.updateFileProgress(queueItem);
            }
        });

        // Upload complete
        xhr.addEventListener('load', () => {
            this.activeUploads--;
            
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        queueItem.status = 'completed';
                        queueItem.response = response;
                        this.handleUploadSuccess(queueItem);
                    } else {
                        queueItem.status = 'error';
                        queueItem.error = response.message || 'Error desconocido';
                        this.handleUploadError(queueItem);
                    }
                } catch (e) {
                    queueItem.status = 'error';
                    queueItem.error = 'Error al procesar respuesta del servidor';
                    this.handleUploadError(queueItem);
                }
            } else {
                queueItem.status = 'error';
                queueItem.error = `Error del servidor: ${xhr.status}`;
                this.handleUploadError(queueItem);
            }

            this.updateFileStatus(queueItem);
            this.processQueue(); // Continue with next files
        });

        // Upload error
        xhr.addEventListener('error', () => {
            this.activeUploads--;
            queueItem.status = 'error';
            
            // Determine error type for better user feedback
            if (!navigator.onLine) {
                queueItem.error = 'Sin conexión a internet';
            } else {
                queueItem.error = 'Error de conexión con el servidor';
            }
            
            this.handleUploadError(queueItem);
            this.updateFileStatus(queueItem);
            this.processQueue();
        });

        // Upload abort
        xhr.addEventListener('abort', () => {
            this.activeUploads--;
            queueItem.status = 'cancelled';
            this.updateFileStatus(queueItem);
            this.processQueue();
        });

        xhr.open('POST', this.options.uploadUrl);
        xhr.send(formData);
    }

    retryUpload(fileId) {
        const queueItem = this.uploadQueue.find(item => item.id === fileId);
        if (queueItem && queueItem.retryCount < queueItem.maxRetries) {
            queueItem.retryCount++;
            queueItem.status = 'pending';
            queueItem.progress = 0;
            queueItem.error = null;
            this.updateFileStatus(queueItem);
            this.processQueue();
        }
    }

    cancelUpload(fileId) {
        const queueItem = this.uploadQueue.find(item => item.id === fileId);
        if (queueItem) {
            if (queueItem.xhr && queueItem.status === 'uploading') {
                queueItem.xhr.abort();
            }
            
            // Remove from queue
            const index = this.uploadQueue.indexOf(queueItem);
            if (index > -1) {
                this.uploadQueue.splice(index, 1);
            }

            // Remove from UI
            this.removeFilePreview(queueItem);
        }
    }

    renderFilePreview(queueItem) {
        const container = document.querySelector('[data-upload-previews]');
        if (!container) return;

        const previewElement = document.createElement('div');
        previewElement.className = 'upload-preview-item';
        previewElement.dataset.fileId = queueItem.id;
        
        previewElement.innerHTML = `
            <div class="flex items-center p-3 bg-gray-50 rounded-lg border">
                <div class="flex-shrink-0 w-12 h-12 bg-gray-200 rounded overflow-hidden">
                    <img class="w-full h-full object-cover file-thumbnail" alt="Preview">
                </div>
                <div class="flex-1 ml-3">
                    <div class="text-sm font-medium text-gray-900 file-name">${queueItem.file.name}</div>
                    <div class="text-xs text-gray-500 file-size">${this.formatFileSize(queueItem.file.size)}</div>
                    <div class="mt-1">
                        <div class="w-full bg-gray-200 rounded-full h-2 progress-bar">
                            <div class="bg-blue-600 h-2 rounded-full progress-fill" style="width: 0%"></div>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 status-text">Preparando...</div>
                    </div>
                </div>
                <div class="flex-shrink-0 ml-3 file-actions">
                    <button type="button" class="text-gray-400 hover:text-gray-600" data-cancel-upload data-file-id="${queueItem.id}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;

        container.appendChild(previewElement);

        // Generate thumbnail
        this.generateThumbnail(queueItem.file, previewElement.querySelector('.file-thumbnail'));
    }

    generateThumbnail(file, imgElement) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imgElement.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    updateFileStatus(queueItem) {
        const element = document.querySelector(`[data-file-id="${queueItem.id}"]`);
        if (!element) return;

        const statusText = element.querySelector('.status-text');
        const progressFill = element.querySelector('.progress-fill');
        const actions = element.querySelector('.file-actions');

        switch (queueItem.status) {
            case 'pending':
                statusText.textContent = 'En cola...';
                statusText.className = 'text-xs text-gray-500 mt-1 status-text';
                break;
            case 'uploading':
                statusText.textContent = `Subiendo... ${queueItem.progress}%`;
                statusText.className = 'text-xs text-blue-600 mt-1 status-text';
                break;
            case 'completed':
                statusText.textContent = 'Completado';
                statusText.className = 'text-xs text-green-600 mt-1 status-text';
                progressFill.style.width = '100%';
                progressFill.className = 'bg-green-600 h-2 rounded-full progress-fill';
                actions.innerHTML = `
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                `;
                break;
            case 'error':
                statusText.textContent = queueItem.error || 'Error al subir';
                statusText.className = 'text-xs text-red-600 mt-1 status-text';
                progressFill.className = 'bg-red-600 h-2 rounded-full progress-fill';
                
                if (queueItem.retryCount < queueItem.maxRetries) {
                    actions.innerHTML = `
                        <button type="button" class="text-blue-600 hover:text-blue-800 mr-2" data-retry-upload data-file-id="${queueItem.id}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <button type="button" class="text-gray-400 hover:text-gray-600" data-cancel-upload data-file-id="${queueItem.id}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    `;
                }
                break;
            case 'cancelled':
                element.remove();
                break;
        }
    }

    updateFileProgress(queueItem) {
        const element = document.querySelector(`[data-file-id="${queueItem.id}"]`);
        if (!element) return;

        const progressFill = element.querySelector('.progress-fill');
        const statusText = element.querySelector('.status-text');
        
        progressFill.style.width = `${queueItem.progress}%`;
        statusText.textContent = `Subiendo... ${queueItem.progress}%`;
    }

    removeFilePreview(queueItem) {
        const element = document.querySelector(`[data-file-id="${queueItem.id}"]`);
        if (element) {
            element.remove();
        }
    }

    handleUploadSuccess(queueItem) {
        // Emit custom event for successful upload
        const event = new CustomEvent('uploadSuccess', {
            detail: {
                file: queueItem.file,
                response: queueItem.response
            }
        });
        document.dispatchEvent(event);

        // Auto-remove completed uploads after delay
        setTimeout(() => {
            this.removeFilePreview(queueItem);
        }, 3000);
    }

    handleUploadError(queueItem) {
        // Emit custom event for upload error
        const event = new CustomEvent('uploadError', {
            detail: {
                file: queueItem.file,
                error: queueItem.error
            }
        });
        document.dispatchEvent(event);
    }

    showErrors(errors) {
        // Show errors in a notification or alert
        const errorContainer = document.querySelector('[data-upload-errors]');
        if (errorContainer) {
            errorContainer.innerHTML = errors.map(error => 
                `<div class="text-sm text-red-600 mb-1">${error}</div>`
            ).join('');
            errorContainer.classList.remove('hidden');
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                errorContainer.classList.add('hidden');
            }, 5000);
        } else {
            // Fallback to alert
            alert('Errores de validación:\n' + errors.join('\n'));
        }
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Public API methods
    clearQueue() {
        this.uploadQueue.forEach(item => {
            if (item.xhr && item.status === 'uploading') {
                item.xhr.abort();
            }
        });
        this.uploadQueue = [];
        this.activeUploads = 0;
        
        const container = document.querySelector('[data-upload-previews]');
        if (container) {
            container.innerHTML = '';
        }

        // Clear error messages
        const errorContainer = document.querySelector('[data-upload-errors]');
        if (errorContainer) {
            errorContainer.classList.add('hidden');
        }
    }

    getQueueStatus() {
        return {
            total: this.uploadQueue.length,
            pending: this.uploadQueue.filter(item => item.status === 'pending').length,
            uploading: this.uploadQueue.filter(item => item.status === 'uploading').length,
            completed: this.uploadQueue.filter(item => item.status === 'completed').length,
            error: this.uploadQueue.filter(item => item.status === 'error').length
        };
    }

    // Pause/Resume functionality
    pauseUploads() {
        this.uploadQueue.forEach(item => {
            if (item.xhr && item.status === 'uploading') {
                item.xhr.abort();
                item.status = 'paused';
                this.activeUploads--;
            }
        });
    }

    resumeUploads() {
        this.uploadQueue.forEach(item => {
            if (item.status === 'paused') {
                item.status = 'pending';
            }
        });
        this.processQueue();
    }

    // Batch operations
    retryAllFailed() {
        const failedItems = this.uploadQueue.filter(item => 
            item.status === 'error' && item.retryCount < item.maxRetries
        );
        
        failedItems.forEach(item => {
            item.retryCount++;
            item.status = 'pending';
            item.progress = 0;
            item.error = null;
            this.updateFileStatus(item);
        });

        this.processQueue();
    }

    removeAllCompleted() {
        const completedItems = this.uploadQueue.filter(item => item.status === 'completed');
        completedItems.forEach(item => {
            this.removeFilePreview(item);
            const index = this.uploadQueue.indexOf(item);
            if (index > -1) {
                this.uploadQueue.splice(index, 1);
            }
        });
    }

    // Configuration updates
    updateConfig(newOptions) {
        this.options = { ...this.options, ...newOptions };
    }

    // Event listeners for external integration
    addEventListener(eventType, callback) {
        document.addEventListener(eventType, callback);
    }

    removeEventListener(eventType, callback) {
        document.removeEventListener(eventType, callback);
    }
}

// Export for use in other modules
window.UploadManager = UploadManager;

// Integration with Alpine.js components
window.createUploadManager = function(options) {
    return new UploadManager(options);
};

// Auto-initialize if upload elements are present
document.addEventListener('DOMContentLoaded', () => {
    const uploadZone = document.querySelector('[data-drop-zone]');
    if (uploadZone) {
        const uploadUrl = uploadZone.dataset.uploadUrl || '/admin/gallery';
        window.galleryUploadManager = new UploadManager({
            uploadUrl: uploadUrl
        });
    }

    // Initialize for elements with data-upload-manager attribute
    const managedElements = document.querySelectorAll('[data-upload-manager]');
    managedElements.forEach(element => {
        const config = JSON.parse(element.dataset.uploadManager || '{}');
        const manager = new UploadManager({
            uploadUrl: config.uploadUrl || '/admin/gallery',
            maxFileSize: config.maxFileSize || 5 * 1024 * 1024,
            maxFiles: config.maxFiles || 10,
            ...config
        });
        
        // Store reference for external access
        element.uploadManager = manager;
    });
});