import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',

        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#dc2626',
                secondary: '#111827',
                light: '#ffffff',
                accent: '#9ca3af',
                metal: '#6b7280',
                graylight: '#e5e7eb',
                graymuted: '#9ca3af',
                background: '#ffffff',
                surface: '#f3f4f6',
                muted: '#6b7280',
                success: '#16a34a',
                danger: '#dc2626',
                warning: '#f59e0b',
                info: '#2563eb',
            },
        },
    },

    plugins: [forms],
};
