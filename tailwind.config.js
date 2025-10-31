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
                primary: '#B71C1C',
                secondary: '#1C1C1C',
                accent: '#C0C0C0',
                background: '#FFFFFF',
                surface: '#D3D3D3',
                muted: '#9E9E9E',
                danger: '#E53935',
                success: '#43A047',
                warning: '#FDD835',
                info: '#1E88E5',
            },
        },
    },

    plugins: [forms],
};
