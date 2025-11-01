<div class="bg-light rounded-lg shadow-lg overflow-hidden">
    <!-- Image Display -->
    <div class="relative">
        <img 
            src="{{ $galleryImage->image_url }}" 
            alt="{{ $galleryImage->alt_text ?: 'Imagen de galería' }}"
            class="w-full h-64 object-cover"
        >
        
        <!-- Status Badge -->
        <div class="absolute top-4 right-4">
            @if($galleryImage->is_active)
                <span class="bg-success text-light px-3 py-1 rounded-full text-sm font-medium">
                    <i class="fas fa-eye mr-1"></i>{{ __('gallery.admin.active') }}
                </span>
            @else
                <span class="bg-metal text-light px-3 py-1 rounded-full text-sm font-medium">
                    <i class="fas fa-eye-slash mr-1"></i>{{ __('gallery.admin.inactive') }}
                </span>
            @endif
        </div>
    </div>

    <!-- Image Information -->
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Info -->
            <div>
                <h3 class="text-lg font-semibold text-secondary mb-4">{{ __('gallery.admin.basic_info') }}</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-metal">{{ __('gallery.admin.original_name') }}</label>
                        <p class="text-secondary">{{ $galleryImage->original_name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-metal">{{ __('gallery.admin.alt_text') }}</label>
                        <p class="text-secondary">{{ $galleryImage->alt_text ?: __('gallery.admin.not_specified') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-metal">{{ __('gallery.admin.status') }}</label>
                        <p class="text-secondary">
                            @if($galleryImage->is_active)
                                <span class="text-success">{{ __('gallery.admin.active') }}</span>
                            @else
                                <span class="text-metal">{{ __('gallery.admin.inactive') }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Technical Info -->
            <div>
                <h3 class="text-lg font-semibold text-secondary mb-4">{{ __('gallery.admin.technical_info') }}</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-metal">{{ __('gallery.admin.file_size') }}</label>
                        <p class="text-secondary">{{ number_format($galleryImage->size / 1024, 1) }} KB</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-metal">{{ __('gallery.admin.mime_type') }}</label>
                        <p class="text-secondary">{{ $galleryImage->mime_type }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-metal">{{ __('gallery.admin.display_order') }}</label>
                        <p class="text-secondary">{{ $galleryImage->display_order }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-metal">{{ __('gallery.admin.upload_date') }}</label>
                        <p class="text-secondary">{{ $galleryImage->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    @if($galleryImage->updated_at != $galleryImage->created_at)
                        <div>
                            <label class="block text-sm font-medium text-metal">{{ __('gallery.admin.last_modified') }}</label>
                            <p class="text-secondary">{{ $galleryImage->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- URLs -->
        <div class="mt-6 pt-6 border-t border-accent">
            <h3 class="text-lg font-semibold text-secondary mb-4">{{ __('gallery.admin.access_urls') }}</h3>
            
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-metal">{{ __('gallery.admin.image_url') }}</label>
                    <div class="flex items-center space-x-2">
                        <input 
                            type="text" 
                            value="{{ $galleryImage->image_url }}" 
                            readonly
                            class="flex-1 px-3 py-2 bg-surface border border-accent rounded text-sm"
                        >
                        <button 
                            onclick="copyToClipboard('{{ $galleryImage->image_url }}')"
                            class="bg-accent hover:bg-gray-500 text-light px-3 py-2 rounded text-sm transition-colors"
                            title="{{ __('gallery.admin.copy_url') }}"
                        >
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-metal">{{ __('gallery.admin.thumbnail_url') }}</label>
                    <div class="flex items-center space-x-2">
                        <input 
                            type="text" 
                            value="{{ $galleryImage->thumbnail_url }}" 
                            readonly
                            class="flex-1 px-3 py-2 bg-surface border border-accent rounded text-sm"
                        >
                        <button 
                            onclick="copyToClipboard('{{ $galleryImage->thumbnail_url }}')"
                            class="bg-accent hover:bg-gray-500 text-light px-3 py-2 rounded text-sm transition-colors"
                            title="{{ __('gallery.admin.copy_url') }}"
                        >
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 pt-6 border-t border-accent flex flex-wrap gap-3">
            <a 
                href="{{ route('admin.gallery.edit', $galleryImage) }}"
                class="bg-primary hover:bg-secondary text-light px-4 py-2 rounded transition-colors"
            >
                <i class="fas fa-edit mr-2"></i>
                {{ __('admin.buttons.edit') }}
            </a>
            
            <form action="{{ route('admin.gallery.toggle_active', $galleryImage) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button 
                    type="submit"
                    class="{{ $galleryImage->is_active ? 'bg-metal hover:bg-gray-500' : 'bg-success hover:bg-green-600' }} text-light px-4 py-2 rounded transition-colors"
                >
                    <i class="fas {{ $galleryImage->is_active ? 'fa-eye-slash' : 'fa-eye' }} mr-2"></i>
                    {{ $galleryImage->is_active ? __('admin.buttons.deactivate') : __('admin.buttons.activate') }}
                </button>
            </form>
            
            <form 
                action="{{ route('admin.gallery.destroy', $galleryImage) }}" 
                method="POST" 
                class="inline"
                onsubmit="return confirm('{{ __('admin.messages.confirm_delete') }} {{ __('admin.messages.action_irreversible') }}')"
            >
                @csrf
                @method('DELETE')
                <button 
                    type="submit"
                    class="bg-danger hover:bg-red-600 text-light px-4 py-2 rounded transition-colors"
                >
                    <i class="fas fa-trash mr-2"></i>
                    {{ __('admin.buttons.delete') }}
                </button>
            </form>
        </div>
    </div>
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