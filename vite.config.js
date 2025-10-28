import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/app.css',
                'node_modules/@fullcalendar/core/main.css',
                'node_modules/@fullcalendar/daygrid/main.css',
                'node_modules/@fullcalendar/timegrid/main.css',
                'node_modules/@fullcalendar/bootstrap/main.css'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
        },
    },
});
