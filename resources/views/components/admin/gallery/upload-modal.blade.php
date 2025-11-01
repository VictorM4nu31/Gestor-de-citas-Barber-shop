@props([
    'uploadRoute' => ''
])

<div 
    class="upload-modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    x-data="uploadModal()"
    x-show="isOpen"
    x-transition
    @open-upload-modal.window="openModal()"
    @close-upload-modal.window="closeModal()"
    @upload-completed.window="handleUploadCompleted()"
    @keydown.escape.window="closeModal()"
>
    <!-- Modal Container -->
    <div 
        class="bg-light rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
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
                    <h2 class="text-2xl font-semibold">Subir Imágenes a la Galería</h2>
                    <p class="text-gray-300 mt-1">Selecciona o arrastra las imágenes que deseas agregar</p>
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
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
            <!-- Upload Instructions -->
            <div class="bg-primary bg-opacity-10 border border-primary rounded-lg p-4 mb-6">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-info-circle text-primary mt-1"></i>
                    <div>
                        <h3 class="font-medium text-secondary mb-2">Instrucciones de Subida</h3>
                        <ul class="text-sm text-metal space-y-1">
                            <li>• Formatos soportados: JPG, PNG, WEBP</li>
                            <li>• Tamaño máximo por imagen: 5MB</li>
                            <li>• Máximo 10 imágenes por vez</li>
                            <li>• Las imágenes se redimensionarán automáticamente</li>
                            <li>• Se generarán miniaturas para optimizar la carga</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Upload Zone Component -->
            <x-admin.gallery.upload-zone :upload-route="$uploadRoute" />

            <!-- Upload Tips -->
            <div class="mt-6 bg-surface border border-accent rounded-lg p-4">
                <h4 class="font-medium text-secondary mb-3 flex items-center">
                    <i class="fas fa-lightbulb text-warning mr-2"></i>
                    Consejos para mejores resultados
                </h4>
                <div class="grid md:grid-cols-2 gap-4 text-sm text-metal">
                    <div>
                        <h5 class="font-medium text-secondary mb-2">Calidad de imagen</h5>
                        <ul class="space-y-1">
                            <li>• Usa imágenes de alta resolución</li>
                            <li>• Evita imágenes borrosas o pixeladas</li>
                            <li>• Prefiere formato JPG para fotos</li>
                            <li>• Usa PNG para imágenes con transparencia</li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-medium text-secondary mb-2">Accesibilidad</h5>
                        <ul class="space-y-1">
                            <li>• Agrega texto alternativo descriptivo</li>
                            <li>• Describe el contenido de la imagen</li>
                            <li>• Evita texto redundante como "imagen de..."</li>
                            <li>• Sé específico y conciso</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-surface border-t border-accent p-6">
            <div class="flex justify-between items-center">
                <div class="text-sm text-metal">
                    <i class="fas fa-shield-alt text-success mr-1"></i>
                    Las imágenes se procesan de forma segura en el servidor
                </div>
                <button 
                    @click="closeModal()"
                    class="bg-accent hover:bg-gray-600 text-light py-2 px-6 rounded transition-colors"
                >
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function uploadModal() {
    return {
        isOpen: false,

        openModal() {
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.isOpen = false;
            document.body.style.overflow = '';
        },

        handleUploadCompleted() {
            // Show success message
            this.$dispatch('show-success', { 
                message: 'Imágenes subidas correctamente' 
            });
            
            // Close modal after a short delay
            setTimeout(() => {
                this.closeModal();
                // Refresh the page to show new images
                window.location.reload();
            }, 1500);
        }
    }
}
</script>
@endpush