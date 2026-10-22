import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#ecfdf5',
                    100: '#d1fae5',
                    200: '#a7f3d0',
                    300: '#6ee7b7',
                    400: '#34d399',
                    500: '#10b981',
                    600: '#059669', // Primary brand color
                    700: '#047857',
                    800: '#065f46',
                    900: '#064e3b',
                    950: '#022c22',
                },
                forest: {
                    DEFAULT: '#1F2A24',
                    50: '#f4f6f4',
                    100: '#e5e9e5',
                    800: '#2b3b32',
                    900: '#1F2A24',
                    950: '#141B17',
                },
                olive: '#374A3C',
                moemenGreen: '#506055',
                sage: '#AF806F',
                lightSage: '#A7B7A7',
            },
            fontFamily: {
                sans: ['Cairo', 'Inter', ...defaultTheme.fontFamily.sans],
                cairo: ['Cairo', 'sans-serif'],
                inter: ['Inter', 'sans-serif'],
            },
        },
    },

    plugins: [forms],
};
