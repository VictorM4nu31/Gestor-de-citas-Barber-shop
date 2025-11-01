<div class="bg-background rounded-lg shadow-lg p-6">
    <form action="{{ route('admin.gallery.update', $galleryImage) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Image Preview -->
        <div class="mb-6 text-center">
            <img 
                src="{{ $galleryImage->thumbnail_url }}" 
                alt="{{ $galleryImage->alt_text ?: 'Imagen de galería' }}"
                class="max-w-full h-48 object-cover rounded-lg mx-auto shadow-md"
            >
            <p class="text-sm text-muted mt-2">{{ $galleryImage->original_name }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <!-- Alt Text -->
                <div>
                    <x-form.input 
                        name="alt_text"
                        label="{{ __('admin.labels.alt_text') }}"
                        :value="old('alt_text', $galleryImage->alt_text)"
                        placeholder="{{ __('forms.placeholders.enter_description') }}"
                    />
                    <p class="text-xs text-muted mt-1">
                        {{ __('admin.labels.alt_text_help') }}
                    </p>
                </div>

                <!-- Status -->
                <div>
                    <x-form.checkbox 
                        name="is_active"
                        label="{{ __('admin.labels.image_active') }}"
                        value="1"
                        :checked="old('is_active', $galleryImage->is_active)"
                    />
                </div>

                <!-- Display Order -->
                <div>
                    <x-form.input 
                        name="display_order"
                        type="number"
                        label="{{ __('admin.labels.display_order') }}"
                        :value="old('display_order', $galleryImage->display_order)"
                        min="1"
                    />
                    <p class="text-xs text-muted mt-1">
                        {{ __('admin.labels.display_order_help') }}
                    </p>
                </div>
            </div>

            <!-- Right Column - Image Info (Read Only) -->
            <div class="bg-surface rounded-lg p-4">
                <h4 class="text-sm font-medium text-secondary mb-3">{{ __('admin.labels.image_info') }}</h4>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-muted">{{ __('admin.labels.name') }}:</span>
                        <span class="text-secondary font-medium">{{ $galleryImage->original_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted">{{ __('admin.labels.size') }}:</span>
                        <span class="text-secondary">{{ number_format($galleryImage->size / 1024, 1) }} KB</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted">{{ __('admin.labels.type') }}:</span>
                        <span class="text-secondary">{{ $galleryImage->mime_type }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted">{{ __('admin.labels.uploaded') }}:</span>
                        <span class="text-secondary">{{ $galleryImage->created_at->format('d/m/Y') }}</span>
                    </div>
                    @if($galleryImage->updated_at != $galleryImage->created_at)
                        <div class="flex justify-between">
                            <span class="text-muted">{{ __('admin.labels.modified') }}:</span>
                            <span class="text-secondary">{{ $galleryImage->updated_at->format('d/m/Y') }}</span>
                        </div>
                    @endif
                </div>

                <!-- URLs Section -->
                <div class="mt-4 pt-4 border-t border-muted">
                    <h5 class="text-xs font-medium text-muted mb-2">{{ __('admin.labels.access_urls') }}</h5>
                    
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-muted">{{ __('admin.labels.full_image') }}:</label>
                            <div class="flex items-center space-x-1">
                                <input 
                                    type="text" 
                                    value="{{ $galleryImage->image_url }}" 
                                    readonly
                                    class="flex-1 px-2 py-1 bg-surface border border-muted rounded text-xs"
                                >
                                <x-ui.button 
                                    type="secondary"
                                    size="sm"
                                    onclick="copyToClipboard('{{ $galleryImage->image_url }}')"
                                    title="{{ __('admin.labels.copy') }}"
                                >
                                    <i class="fas fa-copy"></i>
                                </x-ui.button>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs text-muted">{{ __('admin.labels.thumbnail') }}:</label>
                            <div class="flex items-center space-x-1">
                                <input 
                                    type="text" 
                                    value="{{ $galleryImage->thumbnail_url }}" 
                                    readonly
                                    class="flex-1 px-2 py-1 bg-surface border border-muted rounded text-xs"
                                >
                                <x-ui.button 
                                    type="secondary"
                                    size="sm"
                                    onclick="copyToClipboard('{{ $galleryImage->thumbnail_url }}')"
                                    title="{{ __('admin.labels.copy') }}"
                                >
                                    <i class="fas fa-copy"></i>
                                </x-ui.button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-3 mt-6 pt-6 border-t border-muted">
            <x-ui.button 
                href="{{ route('admin.gallery.show', $galleryImage) }}"
                type="secondary"
            >
                {{ __('admin.buttons.cancel') }}
            </x-ui.button>
            <x-ui.button 
                type="primary"
                submit="true"
            >
                <i class="fas fa-save mr-2"></i>
                {{ __('admin.buttons.save') }}
            </x-ui.button>
        </div>
    </form>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-success text-light p-3 rounded-lg z-50';
        notification.innerHTML = '<i class="fas fa-check mr-2"></i>{{ __('gallery.admin.url_copied') }}';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    });
}
</script>