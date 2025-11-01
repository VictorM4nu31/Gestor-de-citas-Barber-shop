/**
 * Lazy Loading Module
 * Implements intersection observer for lazy loading with fallback support
 */

class LazyLoader {
    constructor(options = {}) {
        this.options = {
            selector: options.selector || '[data-lazy]',
            rootMargin: options.rootMargin || '50px',
            threshold: options.threshold || 0.1,
            placeholderClass: options.placeholderClass || 'lazy-placeholder',
            loadingClass: options.loadingClass || 'lazy-loading',
            loadedClass: options.loadedClass || 'lazy-loaded',
            errorClass: options.errorClass || 'lazy-error',
            fadeInDuration: options.fadeInDuration || 300,
            retryAttempts: options.retryAttempts || 3,
            retryDelay: options.retryDelay || 1000,
            ...options
        };

        this.observer = null;
        this.images = new Map();
        this.supportsIntersectionObserver = 'IntersectionObserver' in window;
        this.supportsWebP = null;

        this.init();
    }

    init() {
        this.addStyles();
        this.checkWebPSupport();
        
        if (this.supportsIntersectionObserver) {
            this.setupIntersectionObserver();
        } else {
            this.setupFallback();
        }
        
        this.loadImages();
        this.bindEvents();
    }

    addStyles() {
        if (document.getElementById('lazy-loading-styles')) return;

        const styles = document.createElement('style');
        styles.id = 'lazy-loading-styles';
        styles.textContent = `
            .lazy-placeholder {
                background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
                background-size: 200% 100%;
                animation: loading-shimmer 1.5s infinite;
                position: relative;
                overflow: hidden;
            }
            
            .lazy-placeholder::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 40px;
                height: 40px;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23999'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: center;
                background-size: contain;
                opacity: 0.5;
            }
            
            @keyframes loading-shimmer {
                0% {
                    background-position: -200% 0;
                }
                100% {
                    background-position: 200% 0;
                }
            }
            
            .lazy-loading {
                opacity: 0;
                transition: opacity ${this.options.fadeInDuration}ms ease-in-out;
            }
            
            .lazy-loaded {
                opacity: 1;
            }
            
            .lazy-error {
                background: #f8f8f8;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #999;
                font-size: 0.8rem;
                position: relative;
            }
            
            .lazy-error::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 40px;
                height: 40px;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23dc2626'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: center;
                background-size: contain;
            }
            
            .lazy-retry-btn {
                position: absolute;
                bottom: 8px;
                right: 8px;
                background: rgba(0, 0, 0, 0.7);
                color: white;
                border: none;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 0.7rem;
                cursor: pointer;
                transition: background-color 0.2s ease;
            }
            
            .lazy-retry-btn:hover {
                background: rgba(0, 0, 0, 0.9);
            }
            
            /* Responsive placeholder heights */
            .lazy-placeholder.aspect-square {
                aspect-ratio: 1 / 1;
            }
            
            .lazy-placeholder.aspect-video {
                aspect-ratio: 16 / 9;
            }
            
            .lazy-placeholder.aspect-photo {
                aspect-ratio: 4 / 3;
            }
            
            /* Fade-in animation variants */
            .lazy-fade-up {
                transform: translateY(20px);
                transition: opacity ${this.options.fadeInDuration}ms ease-in-out, transform ${this.options.fadeInDuration}ms ease-in-out;
            }
            
            .lazy-fade-up.lazy-loaded {
                transform: translateY(0);
            }
            
            .lazy-scale {
                transform: scale(0.95);
                transition: opacity ${this.options.fadeInDuration}ms ease-in-out, transform ${this.options.fadeInDuration}ms ease-in-out;
            }
            
            .lazy-scale.lazy-loaded {
                transform: scale(1);
            }
        `;
        
        document.head.appendChild(styles);
    }

    async checkWebPSupport() {
        if (this.supportsWebP !== null) return this.supportsWebP;

        return new Promise((resolve) => {
            const webP = new Image();
            webP.onload = webP.onerror = () => {
                this.supportsWebP = webP.height === 2;
                resolve(this.supportsWebP);
            };
            webP.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
        });
    }

    setupIntersectionObserver() {
        const options = {
            root: null,
            rootMargin: this.options.rootMargin,
            threshold: this.options.threshold
        };

        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.loadImage(entry.target);
                    this.observer.unobserve(entry.target);
                }
            });
        }, options);
    }

    setupFallback() {
        // Fallback for browsers without IntersectionObserver
        this.bindScrollEvents();
    }

    bindScrollEvents() {
        let ticking = false;

        const checkImages = () => {
            const images = document.querySelectorAll(`${this.options.selector}:not(.${this.options.loadedClass}):not(.${this.options.loadingClass})`);
            
            images.forEach(img => {
                if (this.isInViewport(img)) {
                    this.loadImage(img);
                }
            });
            
            ticking = false;
        };

        const onScroll = () => {
            if (!ticking) {
                requestAnimationFrame(checkImages);
                ticking = true;
            }
        };

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onScroll, { passive: true });
        
        // Initial check
        checkImages();
    }

    bindEvents() {
        // Handle dynamically added images
        document.addEventListener('DOMContentLoaded', () => {
            this.refresh();
        });

        // Retry button clicks
        document.addEventListener('click', (e) => {
            if (e.target.matches('.lazy-retry-btn')) {
                e.preventDefault();
                const img = e.target.closest(this.options.selector);
                if (img) {
                    this.retryImage(img);
                }
            }
        });

        // Handle visibility changes (tab switching)
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.refresh();
            }
        });
    }

    loadImages() {
        const images = document.querySelectorAll(this.options.selector);
        
        images.forEach(img => {
            this.setupImage(img);
            
            if (this.supportsIntersectionObserver) {
                this.observer.observe(img);
            }
        });

        // If no IntersectionObserver, trigger fallback
        if (!this.supportsIntersectionObserver) {
            setTimeout(() => {
                this.bindScrollEvents();
            }, 100);
        }
    }

    setupImage(img) {
        // Store original data
        const imageData = {
            src: img.dataset.lazy || img.dataset.src,
            srcset: img.dataset.lazySrcset || img.dataset.srcset,
            sizes: img.dataset.sizes,
            alt: img.alt || img.dataset.alt || '',
            retryCount: 0,
            element: img
        };

        this.images.set(img, imageData);

        // Add placeholder class
        img.classList.add(this.options.placeholderClass);

        // Set up placeholder
        this.setupPlaceholder(img);
    }

    setupPlaceholder(img) {
        // Preserve aspect ratio if specified
        const aspectRatio = img.dataset.aspectRatio;
        if (aspectRatio) {
            img.classList.add(`aspect-${aspectRatio}`);
        }

        // Add animation class if specified
        const animation = img.dataset.lazyAnimation;
        if (animation) {
            img.classList.add(`lazy-${animation}`);
        }

        // Set placeholder dimensions if not already set
        if (!img.style.width && !img.style.height && !img.classList.contains('w-full')) {
            const width = img.dataset.width;
            const height = img.dataset.height;
            
            if (width && height) {
                img.style.aspectRatio = `${width} / ${height}`;
            }
        }
    }

    async loadImage(img) {
        const imageData = this.images.get(img);
        if (!imageData || img.classList.contains(this.options.loadingClass)) {
            return;
        }

        // Add loading class
        img.classList.add(this.options.loadingClass);
        img.classList.remove(this.options.errorClass);

        try {
            // Determine the best image source
            const src = await this.getBestImageSrc(imageData);
            
            // Preload the image
            await this.preloadImage(src, imageData.srcset, imageData.sizes);
            
            // Apply the image
            this.applyImage(img, src, imageData);
            
        } catch (error) {
            this.handleImageError(img, error);
        }
    }

    async getBestImageSrc(imageData) {
        let src = imageData.src;
        
        // Check for WebP support and alternative sources
        if (this.supportsWebP && imageData.src) {
            const webpSrc = imageData.src.replace(/\.(jpg|jpeg|png)$/i, '.webp');
            
            // Check if WebP version exists
            try {
                await this.checkImageExists(webpSrc);
                src = webpSrc;
            } catch {
                // Fallback to original format
            }
        }
        
        return src;
    }

    checkImageExists(src) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => resolve(src);
            img.onerror = () => reject(new Error('Image not found'));
            img.src = src;
        });
    }

    preloadImage(src, srcset, sizes) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            
            img.onload = () => resolve(img);
            img.onerror = () => reject(new Error('Failed to load image'));
            
            if (srcset) {
                img.srcset = srcset;
            }
            
            if (sizes) {
                img.sizes = sizes;
            }
            
            img.src = src;
        });
    }

    applyImage(imgElement, src, imageData) {
        // Remove placeholder and loading classes
        imgElement.classList.remove(this.options.placeholderClass, this.options.loadingClass);
        
        // Set image attributes
        imgElement.src = src;
        
        if (imageData.srcset) {
            imgElement.srcset = imageData.srcset;
        }
        
        if (imageData.sizes) {
            imgElement.sizes = imageData.sizes;
        }
        
        if (imageData.alt) {
            imgElement.alt = imageData.alt;
        }

        // Add loaded class with delay for animation
        requestAnimationFrame(() => {
            imgElement.classList.add(this.options.loadedClass);
        });

        // Emit custom event
        const event = new CustomEvent('lazyImageLoaded', {
            detail: {
                element: imgElement,
                src: src,
                imageData: imageData
            }
        });
        imgElement.dispatchEvent(event);

        // Clean up
        this.images.delete(imgElement);
    }

    handleImageError(img, error) {
        const imageData = this.images.get(img);
        
        img.classList.remove(this.options.loadingClass, this.options.placeholderClass);
        img.classList.add(this.options.errorClass);

        // Add retry button if retries are available
        if (imageData && imageData.retryCount < this.options.retryAttempts) {
            this.addRetryButton(img);
        }

        // Emit custom event
        const event = new CustomEvent('lazyImageError', {
            detail: {
                element: img,
                error: error,
                retryCount: imageData ? imageData.retryCount : 0
            }
        });
        img.dispatchEvent(event);
    }

    addRetryButton(img) {
        // Remove existing retry button
        const existingBtn = img.parentNode.querySelector('.lazy-retry-btn');
        if (existingBtn) {
            existingBtn.remove();
        }

        // Create retry button
        const retryBtn = document.createElement('button');
        retryBtn.className = 'lazy-retry-btn';
        retryBtn.textContent = 'Reintentar';
        retryBtn.setAttribute('aria-label', 'Reintentar carga de imagen');

        // Position relative to image
        const container = img.parentNode;
        const containerStyle = window.getComputedStyle(container);
        
        if (containerStyle.position === 'static') {
            container.style.position = 'relative';
        }
        
        container.appendChild(retryBtn);
    }

    retryImage(img) {
        const imageData = this.images.get(img);
        if (!imageData) return;

        imageData.retryCount++;
        
        // Remove error state
        img.classList.remove(this.options.errorClass);
        
        // Remove retry button
        const retryBtn = img.parentNode.querySelector('.lazy-retry-btn');
        if (retryBtn) {
            retryBtn.remove();
        }

        // Retry after delay
        setTimeout(() => {
            this.loadImage(img);
        }, this.options.retryDelay);
    }

    isInViewport(element) {
        const rect = element.getBoundingClientRect();
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;
        const windowWidth = window.innerWidth || document.documentElement.clientWidth;
        
        // Parse rootMargin
        const margin = parseInt(this.options.rootMargin) || 0;
        
        return (
            rect.top <= windowHeight + margin &&
            rect.bottom >= -margin &&
            rect.left <= windowWidth + margin &&
            rect.right >= -margin
        );
    }

    // Public API methods
    refresh() {
        // Find new lazy images
        const newImages = document.querySelectorAll(`${this.options.selector}:not(.${this.options.loadedClass}):not(.${this.options.loadingClass}):not(.${this.options.errorClass})`);
        
        newImages.forEach(img => {
            if (!this.images.has(img)) {
                this.setupImage(img);
                
                if (this.supportsIntersectionObserver && this.observer) {
                    this.observer.observe(img);
                }
            }
        });

        // If no IntersectionObserver, check visibility immediately
        if (!this.supportsIntersectionObserver) {
            newImages.forEach(img => {
                if (this.isInViewport(img)) {
                    this.loadImage(img);
                }
            });
        }
    }

    loadAll() {
        const images = document.querySelectorAll(`${this.options.selector}:not(.${this.options.loadedClass}):not(.${this.options.loadingClass})`);
        
        images.forEach(img => {
            this.loadImage(img);
        });
    }

    reset() {
        // Clear all loaded images
        const images = document.querySelectorAll(`${this.options.selector}.${this.options.loadedClass}`);
        
        images.forEach(img => {
            img.classList.remove(this.options.loadedClass);
            img.classList.add(this.options.placeholderClass);
            img.src = '';
            img.srcset = '';
            
            // Re-setup for lazy loading
            this.setupImage(img);
            
            if (this.supportsIntersectionObserver && this.observer) {
                this.observer.observe(img);
            }
        });
    }

    destroy() {
        if (this.observer) {
            this.observer.disconnect();
        }
        
        this.images.clear();
        
        // Remove event listeners
        window.removeEventListener('scroll', this.onScroll);
        window.removeEventListener('resize', this.onScroll);
    }

    // Utility methods
    static preloadImage(src) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = () => reject(new Error('Failed to preload image'));
            img.src = src;
        });
    }

    static preloadImages(sources) {
        return Promise.all(sources.map(src => LazyLoader.preloadImage(src)));
    }
}

// Export for use in other modules
window.LazyLoader = LazyLoader;

// Auto-initialize
document.addEventListener('DOMContentLoaded', () => {
    const lazyImages = document.querySelectorAll('[data-lazy]');
    if (lazyImages.length > 0) {
        window.galleryLazyLoader = new LazyLoader();
    }
});