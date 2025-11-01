@if($images->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
        @foreach($images as $image)
            <x-admin.gallery.image-card 
                :image="$image"
                :reorder-mode="false"
                :selectable="true"
            />
        @endforeach
    </div>
@else
    <div class="text-center py-12">
        <div class="mx-auto w-24 h-24 bg-accent rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-images text-3xl text-light"></i>
        </div>
        <h3 class="text-xl font-medium text-secondary mb-2">{{ __('gallery.no_images') }}</h3>
        <p class="text-metal mb-6">{{ __('gallery.admin.upload.title') }}</p>
        <a 
            href="{{ route('admin.gallery.create') }}"
            class="bg-primary hover:bg-secondary text-light py-2 px-6 rounded-lg transition-colors inline-flex items-center"
        >
            <i class="fas fa-plus-circle mr-2"></i>
            {{ __('admin.buttons.upload_images') }}
        </a>
    </div>
@endif

<!-- Pagination -->
@if($images->hasPages())
    <div class="mt-8">
        {{ $images->links() }}
    </div>
@endif