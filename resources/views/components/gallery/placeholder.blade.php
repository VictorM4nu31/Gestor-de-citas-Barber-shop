@props(['class' => ''])

<div {{ $attributes->merge(['class' => "gallery-placeholder text-center py-16 {$class}"]) }}>
    <div class="max-w-md mx-auto">
        <div class="w-24 h-24 mx-auto mb-4 bg-muted rounded-full flex items-center justify-center">
            <svg class="w-12 h-12 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                </path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-foreground mb-2">No hay imágenes disponibles</h3>
        <p class="text-muted-foreground">
            Pronto agregaremos nuevas imágenes a nuestra galería.
        </p>
    </div>
</div>