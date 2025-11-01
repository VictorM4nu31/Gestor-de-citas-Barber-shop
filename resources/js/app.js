import './bootstrap';
import 'flowbite';

import Alpine from 'alpinejs';

// Gallery JavaScript modules
import './components/gallery/admin/upload-manager.js';
import './components/gallery/admin/image-reorder.js';
import './components/gallery/public/lightbox.js';
import './components/gallery/public/lazy-loading.js';

window.Alpine = Alpine;

Alpine.start();
