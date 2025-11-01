/**
 * Public Lightbox Module
 * Handles lightbox functionality with keyboard navigation and touch gestures
 */

class GalleryLightbox {
    constructor(options = {}) {
        this.options = {
            selector: options.selector || '[data-lightbox-trigger]',
            gallery: options.gallery || 'gallery',
            closeOnOverlay: options.closeOnOverlay !== false,
            showNavigation: options.showNavigation !== false,
            showCounter: options.showCounter !== false,
            enableKeyboard: options.enableKeyboard !== false,
            enableTouch: options.enableTouch !== false,
            enableZoom: options.enableZoom !== false,
            animationDuration: options.animationDuration || 300,
            ...options
        };

        this.isOpen = false;
        this.currentIndex = 0;
        this.images = [];
        this.lightboxElement = null;
        this.imageElement = null;
        this.isLoading = false;
        
        // Touch and zoom properties
        this.touchStartX = 0;
        this.touchStartY = 0;
        this.touchEndX = 0;
        this.touchEndY = 0;
        this.touchThreshold = 50;
        this.isZoomed = false;
        this.zoomLevel = 1;
        this.maxZoom = 3;
        this.minZoom = 1;
        this.panX = 0;
        this.panY = 0;
        this.isPanning = false;
        this.lastTouchDistance = 0;

        this.init();
    }

    init() {
        this.bindEvents();
        this.createLightbox();
        this.loadImages();
    }

    bindEvents() {
        // Image click events
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest(this.options.selector);
            if (trigger) {
                e.preventDefault();
                const gallery = trigger.dataset.gallery || this.options.gallery;
                const imageUrl = trigger.dataset.lightboxImage || trigger.src || trigger.href;
                this.openLightbox(imageUrl, gallery);
            }
        });

        // Keyboard events
        if (this.options.enableKeyboard) {
            document.addEventListener('keydown', (e) => this.handleKeydown(e));
        }

        // Window resize
        window.addEventListener('resize', () => this.handleResize());
    }

    loadImages() {
        const triggers = document.querySelectorAll(this.options.selector);
        const galleries = {};

        triggers.forEach(trigger => {
            const gallery = trigger.dataset.gallery || this.options.gallery;
            const imageUrl = trigger.dataset.lightboxImage || trigger.src || trigger.href;
            const caption = trigger.dataset.caption || trigger.alt || '';
            
            if (!galleries[gallery]) {
                galleries[gallery] = [];
            }
            
            galleries[gallery].push({
                url: imageUrl,
                caption: caption,
                element: trigger
            });
        });

        this.galleries = galleries;
    }

    createLightbox() {
        this.lightboxElement = document.createElement('div');
        this.lightboxElement.className = 'lightbox-overlay';
        this.lightboxElement.innerHTML = `
            <div class="lightbox-container">
                <div class="lightbox-content">
                    <div class="lightbox-loader">
                        <div class="loader-spinner"></div>
                    </div>
                    <div class="lightbox-image-container">
                        <img class="lightbox-image" alt="">
                    </div>
                    <div class="lightbox-caption"></div>
                </div>
                
                ${this.options.showNavigation ? `
                    <button class="lightbox-nav lightbox-prev" aria-label="Imagen anterior">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button class="lightbox-nav lightbox-next" aria-label="Siguiente imagen">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                ` : ''}
                
                <button class="lightbox-close" aria-label="Cerrar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                ${this.options.showCounter ? `
                    <div class="lightbox-counter">
                        <span class="current-index">1</span> / <span class="total-images">1</span>
                    </div>
                ` : ''}
                
                ${this.options.enableZoom ? `
                    <div class="lightbox-zoom-controls">
                        <button class="zoom-in" aria-label="Acercar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path>
                            </svg>
                        </button>
                        <button class="zoom-out" aria-label="Alejar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM7 10h6"></path>
                            </svg>
                        </button>
                        <button class="zoom-reset" aria-label="Restablecer zoom">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                    </div>
                ` : ''}
            </div>
        `;

        // Add styles
        this.addStyles();
        
        // Append to body
        document.body.appendChild(this.lightboxElement);
        
        // Get references
        this.imageElement = this.lightboxElement.querySelector('.lightbox-image');
        this.imageContainer = this.lightboxElement.querySelector('.lightbox-image-container');
        this.captionElement = this.lightboxElement.querySelector('.lightbox-caption');
        this.loaderElement = this.lightboxElement.querySelector('.lightbox-loader');
        
        // Bind lightbox events
        this.bindLightboxEvents();
    }

    addStyles() {
        if (document.getElementById('lightbox-styles')) return;

        const styles = document.createElement('style');
        styles.id = 'lightbox-styles';
        styles.textContent = `
            .lightbox-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.9);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                visibility: hidden;
                transition: opacity ${this.options.animationDuration}ms ease, visibility ${this.options.animationDuration}ms ease;
            }
            
            .lightbox-overlay.active {
                opacity: 1;
                visibility: visible;
            }
            
            .lightbox-container {
                position: relative;
                max-width: 90vw;
                max-height: 90vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .lightbox-content {
                position: relative;
                display: flex;
                flex-direction: column;
                align-items: center;
                max-width: 100%;
                max-height: 100%;
            }
            
            .lightbox-image-container {
                position: relative;
                overflow: hidden;
                cursor: grab;
                max-width: 100%;
                max-height: 80vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .lightbox-image-container.zoomed {
                cursor: grab;
            }
            
            .lightbox-image-container.panning {
                cursor: grabbing;
            }
            
            .lightbox-image {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                transition: transform 0.3s ease;
                user-select: none;
                -webkit-user-drag: none;
            }
            
            .lightbox-caption {
                color: white;
                text-align: center;
                padding: 1rem;
                max-width: 100%;
                font-size: 0.9rem;
                line-height: 1.4;
            }
            
            .lightbox-loader {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 10;
            }
            
            .loader-spinner {
                width: 40px;
                height: 40px;
                border: 3px solid rgba(255, 255, 255, 0.3);
                border-top: 3px solid white;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            .lightbox-nav {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                background: rgba(0, 0, 0, 0.5);
                color: white;
                border: none;
                padding: 1rem;
                cursor: pointer;
                transition: background-color 0.3s ease;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .lightbox-nav:hover {
                background: rgba(0, 0, 0, 0.8);
            }
            
            .lightbox-nav:disabled {
                opacity: 0.3;
                cursor: not-allowed;
            }
            
            .lightbox-prev {
                left: 2rem;
            }
            
            .lightbox-next {
                right: 2rem;
            }
            
            .lightbox-close {
                position: absolute;
                top: 1rem;
                right: 1rem;
                background: rgba(0, 0, 0, 0.5);
                color: white;
                border: none;
                padding: 0.5rem;
                cursor: pointer;
                transition: background-color 0.3s ease;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .lightbox-close:hover {
                background: rgba(0, 0, 0, 0.8);
            }
            
            .lightbox-counter {
                position: absolute;
                top: 1rem;
                left: 1rem;
                background: rgba(0, 0, 0, 0.5);
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 1rem;
                font-size: 0.8rem;
            }
            
            .lightbox-zoom-controls {
                position: absolute;
                bottom: 1rem;
                right: 1rem;
                display: flex;
                gap: 0.5rem;
            }
            
            .lightbox-zoom-controls button {
                background: rgba(0, 0, 0, 0.5);
                color: white;
                border: none;
                padding: 0.5rem;
                cursor: pointer;
                transition: background-color 0.3s ease;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .lightbox-zoom-controls button:hover {
                background: rgba(0, 0, 0, 0.8);
            }
            
            @media (max-width: 768px) {
                .lightbox-nav {
                    padding: 0.75rem;
                }
                
                .lightbox-prev {
                    left: 1rem;
                }
                
                .lightbox-next {
                    right: 1rem;
                }
                
                .lightbox-counter {
                    top: 0.5rem;
                    left: 0.5rem;
                    padding: 0.25rem 0.75rem;
                    font-size: 0.7rem;
                }
                
                .lightbox-close {
                    top: 0.5rem;
                    right: 0.5rem;
                    padding: 0.25rem;
                }
                
                .lightbox-zoom-controls {
                    bottom: 0.5rem;
                    right: 0.5rem;
                }
            }
        `;
        
        document.head.appendChild(styles);
    }

    bindLightboxEvents() {
        // Close button
        const closeBtn = this.lightboxElement.querySelector('.lightbox-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.closeLightbox());
        }

        // Navigation buttons
        const prevBtn = this.lightboxElement.querySelector('.lightbox-prev');
        const nextBtn = this.lightboxElement.querySelector('.lightbox-next');
        
        if (prevBtn) {
            prevBtn.addEventListener('click', () => this.previousImage());
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', () => this.nextImage());
        }

        // Overlay click to close
        if (this.options.closeOnOverlay) {
            this.lightboxElement.addEventListener('click', (e) => {
                if (e.target === this.lightboxElement) {
                    this.closeLightbox();
                }
            });
        }

        // Touch events
        if (this.options.enableTouch) {
            this.bindTouchEvents();
        }

        // Zoom controls
        if (this.options.enableZoom) {
            this.bindZoomEvents();
        }

        // Image load events
        this.imageElement.addEventListener('load', () => this.handleImageLoad());
        this.imageElement.addEventListener('error', () => this.handleImageError());
    }

    bindTouchEvents() {
        this.imageContainer.addEventListener('touchstart', (e) => this.handleTouchStart(e), { passive: false });
        this.imageContainer.addEventListener('touchmove', (e) => this.handleTouchMove(e), { passive: false });
        this.imageContainer.addEventListener('touchend', (e) => this.handleTouchEnd(e), { passive: false });
        
        // Mouse events for desktop drag
        this.imageContainer.addEventListener('mousedown', (e) => this.handleMouseDown(e));
        this.imageContainer.addEventListener('mousemove', (e) => this.handleMouseMove(e));
        this.imageContainer.addEventListener('mouseup', (e) => this.handleMouseUp(e));
        this.imageContainer.addEventListener('mouseleave', (e) => this.handleMouseUp(e));
        
        // Wheel zoom
        this.imageContainer.addEventListener('wheel', (e) => this.handleWheel(e), { passive: false });
    }

    bindZoomEvents() {
        const zoomInBtn = this.lightboxElement.querySelector('.zoom-in');
        const zoomOutBtn = this.lightboxElement.querySelector('.zoom-out');
        const zoomResetBtn = this.lightboxElement.querySelector('.zoom-reset');
        
        if (zoomInBtn) {
            zoomInBtn.addEventListener('click', () => this.zoomIn());
        }
        
        if (zoomOutBtn) {
            zoomOutBtn.addEventListener('click', () => this.zoomOut());
        }
        
        if (zoomResetBtn) {
            zoomResetBtn.addEventListener('click', () => this.resetZoom());
        }

        // Double-click to zoom
        this.imageElement.addEventListener('dblclick', () => {
            if (this.isZoomed) {
                this.resetZoom();
            } else {
                this.zoomIn();
            }
        });
    }

    openLightbox(imageUrl, gallery = 'gallery') {
        this.images = this.galleries[gallery] || [];
        this.currentIndex = this.images.findIndex(img => img.url === imageUrl);
        
        if (this.currentIndex === -1) {
            this.currentIndex = 0;
        }

        this.isOpen = true;
        document.body.style.overflow = 'hidden';
        
        this.lightboxElement.classList.add('active');
        this.loadCurrentImage();
        this.updateNavigation();
        this.updateCounter();
        
        // Emit custom event
        const event = new CustomEvent('lightboxOpen', {
            detail: {
                imageUrl: imageUrl,
                gallery: gallery,
                index: this.currentIndex
            }
        });
        document.dispatchEvent(event);
    }

    closeLightbox() {
        this.isOpen = false;
        document.body.style.overflow = '';
        
        this.lightboxElement.classList.remove('active');
        this.resetZoom();
        
        // Emit custom event
        const event = new CustomEvent('lightboxClose');
        document.dispatchEvent(event);
    }

    loadCurrentImage() {
        if (!this.images[this.currentIndex]) return;
        
        this.showLoader();
        this.resetZoom();
        
        const currentImage = this.images[this.currentIndex];
        this.imageElement.src = currentImage.url;
        this.imageElement.alt = currentImage.caption;
        
        if (this.captionElement) {
            this.captionElement.textContent = currentImage.caption;
        }
    }

    handleImageLoad() {
        this.hideLoader();
    }

    handleImageError() {
        this.hideLoader();
        this.imageElement.alt = 'Error al cargar la imagen';
        
        if (this.captionElement) {
            this.captionElement.textContent = 'Error al cargar la imagen';
        }
    }

    showLoader() {
        this.isLoading = true;
        if (this.loaderElement) {
            this.loaderElement.style.display = 'block';
        }
    }

    hideLoader() {
        this.isLoading = false;
        if (this.loaderElement) {
            this.loaderElement.style.display = 'none';
        }
    }

    previousImage() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
            this.loadCurrentImage();
            this.updateNavigation();
            this.updateCounter();
        }
    }

    nextImage() {
        if (this.currentIndex < this.images.length - 1) {
            this.currentIndex++;
            this.loadCurrentImage();
            this.updateNavigation();
            this.updateCounter();
        }
    }

    updateNavigation() {
        const prevBtn = this.lightboxElement.querySelector('.lightbox-prev');
        const nextBtn = this.lightboxElement.querySelector('.lightbox-next');
        
        if (prevBtn) {
            prevBtn.disabled = this.currentIndex === 0;
        }
        
        if (nextBtn) {
            nextBtn.disabled = this.currentIndex === this.images.length - 1;
        }
    }

    updateCounter() {
        const currentIndexEl = this.lightboxElement.querySelector('.current-index');
        const totalImagesEl = this.lightboxElement.querySelector('.total-images');
        
        if (currentIndexEl) {
            currentIndexEl.textContent = this.currentIndex + 1;
        }
        
        if (totalImagesEl) {
            totalImagesEl.textContent = this.images.length;
        }
    }

    handleKeydown(e) {
        if (!this.isOpen) return;
        
        switch (e.key) {
            case 'Escape':
                this.closeLightbox();
                break;
            case 'ArrowLeft':
                this.previousImage();
                break;
            case 'ArrowRight':
                this.nextImage();
                break;
            case '+':
            case '=':
                if (this.options.enableZoom) {
                    this.zoomIn();
                }
                break;
            case '-':
                if (this.options.enableZoom) {
                    this.zoomOut();
                }
                break;
            case '0':
                if (this.options.enableZoom) {
                    this.resetZoom();
                }
                break;
        }
    }

    handleResize() {
        if (this.isOpen && this.isZoomed) {
            this.resetZoom();
        }
    }

    // Touch event handlers
    handleTouchStart(e) {
        if (e.touches.length === 1) {
            // Single touch - start pan
            this.touchStartX = e.touches[0].clientX;
            this.touchStartY = e.touches[0].clientY;
            this.isPanning = this.isZoomed;
            
            if (this.isPanning) {
                this.imageContainer.classList.add('panning');
            }
        } else if (e.touches.length === 2) {
            // Two touches - start pinch zoom
            const touch1 = e.touches[0];
            const touch2 = e.touches[1];
            this.lastTouchDistance = Math.hypot(
                touch2.clientX - touch1.clientX,
                touch2.clientY - touch1.clientY
            );
        }
        
        e.preventDefault();
    }

    handleTouchMove(e) {
        if (e.touches.length === 1 && this.isPanning) {
            // Single touch - pan
            const deltaX = e.touches[0].clientX - this.touchStartX;
            const deltaY = e.touches[0].clientY - this.touchStartY;
            
            this.panX += deltaX;
            this.panY += deltaY;
            
            this.updateImageTransform();
            
            this.touchStartX = e.touches[0].clientX;
            this.touchStartY = e.touches[0].clientY;
        } else if (e.touches.length === 2) {
            // Two touches - pinch zoom
            const touch1 = e.touches[0];
            const touch2 = e.touches[1];
            const currentDistance = Math.hypot(
                touch2.clientX - touch1.clientX,
                touch2.clientY - touch1.clientY
            );
            
            if (this.lastTouchDistance > 0) {
                const scale = currentDistance / this.lastTouchDistance;
                this.zoomLevel = Math.max(this.minZoom, Math.min(this.maxZoom, this.zoomLevel * scale));
                this.updateImageTransform();
            }
            
            this.lastTouchDistance = currentDistance;
        }
        
        e.preventDefault();
    }

    handleTouchEnd(e) {
        if (e.touches.length === 0) {
            // All touches ended
            this.touchEndX = e.changedTouches[0].clientX;
            this.touchEndY = e.changedTouches[0].clientY;
            
            if (!this.isPanning) {
                // Check for swipe gestures
                const deltaX = this.touchEndX - this.touchStartX;
                const deltaY = Math.abs(this.touchEndY - this.touchStartY);
                
                if (Math.abs(deltaX) > this.touchThreshold && deltaY < this.touchThreshold) {
                    if (deltaX > 0) {
                        this.previousImage();
                    } else {
                        this.nextImage();
                    }
                }
            }
            
            this.isPanning = false;
            this.imageContainer.classList.remove('panning');
        }
        
        this.lastTouchDistance = 0;
    }

    // Mouse event handlers for desktop drag
    handleMouseDown(e) {
        if (this.isZoomed) {
            this.isPanning = true;
            this.touchStartX = e.clientX;
            this.touchStartY = e.clientY;
            this.imageContainer.classList.add('panning');
            e.preventDefault();
        }
    }

    handleMouseMove(e) {
        if (this.isPanning) {
            const deltaX = e.clientX - this.touchStartX;
            const deltaY = e.clientY - this.touchStartY;
            
            this.panX += deltaX;
            this.panY += deltaY;
            
            this.updateImageTransform();
            
            this.touchStartX = e.clientX;
            this.touchStartY = e.clientY;
        }
    }

    handleMouseUp(e) {
        this.isPanning = false;
        this.imageContainer.classList.remove('panning');
    }

    handleWheel(e) {
        if (this.options.enableZoom) {
            e.preventDefault();
            
            if (e.deltaY < 0) {
                this.zoomIn();
            } else {
                this.zoomOut();
            }
        }
    }

    // Zoom methods
    zoomIn() {
        this.zoomLevel = Math.min(this.maxZoom, this.zoomLevel * 1.2);
        this.updateImageTransform();
    }

    zoomOut() {
        this.zoomLevel = Math.max(this.minZoom, this.zoomLevel / 1.2);
        this.updateImageTransform();
        
        if (this.zoomLevel === this.minZoom) {
            this.resetZoom();
        }
    }

    resetZoom() {
        this.zoomLevel = this.minZoom;
        this.panX = 0;
        this.panY = 0;
        this.isZoomed = false;
        this.updateImageTransform();
        this.imageContainer.classList.remove('zoomed');
    }

    updateImageTransform() {
        this.isZoomed = this.zoomLevel > this.minZoom;
        
        if (this.isZoomed) {
            this.imageContainer.classList.add('zoomed');
        } else {
            this.imageContainer.classList.remove('zoomed');
        }
        
        this.imageElement.style.transform = `scale(${this.zoomLevel}) translate(${this.panX / this.zoomLevel}px, ${this.panY / this.zoomLevel}px)`;
    }

    // Public API methods
    refresh() {
        this.loadImages();
    }

    getCurrentImage() {
        return this.images[this.currentIndex];
    }

    goToImage(index) {
        if (index >= 0 && index < this.images.length) {
            this.currentIndex = index;
            this.loadCurrentImage();
            this.updateNavigation();
            this.updateCounter();
        }
    }
}

// Export for use in other modules
window.GalleryLightbox = GalleryLightbox;

// Auto-initialize
document.addEventListener('DOMContentLoaded', () => {
    window.galleryLightbox = new GalleryLightbox();
});