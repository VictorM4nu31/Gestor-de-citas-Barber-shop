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
                sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
                display: ['DM Serif Display', 'Georgia', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                primary: '#7A321F',
                secondary: '#171513',
                accent: '#D8D0C5',
                background: '#F4F0E8',
                surface: '#FFFDF8',
                muted: '#655F58',
                metal: '#91877B',
                light: '#FFFDF8',
                dark: '#171513',
                copper: '#B86643',
                brass: '#D99A4E',
                ink: '#171513',
                paper: '#F4F0E8',
                line: '#D8D0C5',
                danger: '#A13B32',
                success: '#1D6B52',
                warning: '#8A5A00',
                info: '#1D5870',
                status: {
                    pending: '#8A5A00',
                    confirmed: '#1D5870',
                    attended: '#1D6B52',
                    cancelled: '#A13B32',
                },
            },
        },
    },

    plugins: [forms],
};
