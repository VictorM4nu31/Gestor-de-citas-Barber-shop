<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">{{ __('admin.titles.upload_images') }}</h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.gallery.index') }}" class="bg-accent hover:bg-gray-600 text-light py-2 px-4 rounded inline-flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ __('admin.buttons.back_to_gallery') }}</span>
                </a>
            </div>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-success text-light p-4 rounded mb-6 flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-danger text-light p-4 rounded mb-6">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-circle mr-3"></i>
                    <span class="font-semibold">{{ __('forms.validation.errors_found') }}</span>
                </div>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-light rounded-lg shadow-lg p-6">
            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" id="upload-form">
                @csrf
                
                <!-- File Upload Area -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-secondary mb-2">
                        {{ __('admin.labels.select_images') }}
                    </label>
                    <div 
                        class="border-2 border-dashed border-accent rounded-lg p-8 text-center hover:border-primary transition-colors"
                        id="drop-zone"
                    >
                        <div class="mb-4">
                            <i class="fas fa-cloud-upload-alt text-4xl text-accent"></i>
                        </div>
                        <p class="text-metal mb-2">
                            {{ __('admin.labels.drag_drop_hint') }} 
                            <button type="button" onclick="document.getElementById('file-input').click()" class="text-primary hover:underline">
                                {{ __('admin.labels.click_to_select') }}
                            </button>
                        </p>
                        <p class="text-xs text-metal">
                            {{ __('admin.labels.supported_formats') }}
                        </p>
                        
                        <input 
                            type="file" 
                            id="file-input"
                            name="images[]"
                            multiple 
                            accept="image/*"
                            class="hidden"
                            required
                        >
                    </div>
                </div>

                <!-- Selected Files Preview -->
                <div id="preview-container" class="hidden mb-6">
                    <h4 class="text-sm font-medium text-secondary mb-3">
                        {{ __('admin.labels.selected_images') }} (<span id="file-count">0</span>)
                    </h4>
                    <div id="preview-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 max-h-60 overflow-y-auto">
                        <!-- Preview items will be added here -->
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-accent">
                    <a 
                        href="{{ route('admin.gallery.index') }}"
                        class="px-4 py-2 text-metal hover:text-secondary transition-colors"
                    >
                        {{ __('admin.buttons.cancel') }}
                    </a>
                    <button 
                        type="submit"
                        id="submit-btn"
                        class="bg-primary hover:bg-secondary text-light px-6 py-2 rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled
                    >
                        <i class="fas fa-upload mr-2"></i>
                        {{ __('admin.buttons.upload_images') }}
                    </button>
                </div>
            </form>
        </div>
    </main>

    @push('scripts')
    <script>
        // Translation helper for JavaScript
        const translations = {
            uploading: @json(__('gallery.admin.upload.uploading')),
            upload_success: @json(__('gallery.admin.upload.upload_success')),
            upload_error: @json(__('gallery.admin.upload.upload_error')),
            selected_images: @json(__('admin.labels.selected_images')),
            cancel: @json(__('admin.buttons.cancel')),
            upload_images: @json(__('admin.buttons.upload_images'))
        };

        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('file-input');
            const dropZone = document.getElementById('drop-zone');
            const previewContainer = document.getElementById('preview-container');
            const previewGrid = document.getElementById('preview-grid');
            const fileCount = document.getElementById('file-count');
            const submitBtn = document.getElementById('submit-btn');
            let selectedFiles = [];

            // File input change handler
            fileInput.addEventListener('change', function(e) {
                handleFiles(e.target.files);
            });

            // Drag and drop handlers
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropZone.classList.add('border-primary');
            });

            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-primary');
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-primary');
                handleFiles(e.dataTransfer.files);
            });

            function handleFiles(files) {
                selectedFiles = [];
                previewGrid.innerHTML = '';
                
                Array.from(files).forEach((file, index) => {
                    if (file.type.startsWith('image/') && file.size <= 10 * 1024 * 1024) {
                        selectedFiles.push(file);
                        createPreview(file, index);
                    }
                });

                updateUI();
            }

            function createPreview(file, index) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'relative bg-surface rounded-lg overflow-hidden';
                    previewItem.innerHTML = `
                        <img src="${e.target.result}" alt="${file.name}" class="w-full h-20 object-cover">
                        <button type="button" onclick="removeFile(${index})" class="absolute top-1 right-1 bg-danger text-light rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-700 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="p-2">
                            <p class="text-xs text-secondary truncate" title="${file.name}">${file.name}</p>
                            <p class="text-xs text-metal">${formatFileSize(file.size)}</p>
                        </div>
                    `;
                    previewGrid.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            }

            function updateUI() {
                fileCount.textContent = selectedFiles.length;
                previewContainer.classList.toggle('hidden', selectedFiles.length === 0);
                submitBtn.disabled = selectedFiles.length === 0;
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
            }

            // Make removeFile function global
            window.removeFile = function(index) {
                selectedFiles.splice(index, 1);
                
                // Update file input
                const dt = new DataTransfer();
                selectedFiles.forEach(file => dt.items.add(file));
                fileInput.files = dt.files;
                
                // Recreate previews
                previewGrid.innerHTML = '';
                selectedFiles.forEach((file, newIndex) => {
                    createPreview(file, newIndex);
                });
                
                updateUI();
            };
        });
    </script>
    @endpush
</x-app-layout>