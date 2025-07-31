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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],

    safelist: [
        'bg-yellow-400',
        'bg-yellow-100',
        'text-yellow-900',
        'text-yellow-800',
        // Stat card colors - prevent purging of dynamic classes
        'border-blue-500',
        'text-blue-600',
        'text-blue-500',
        'border-green-500',
        'text-green-600',
        'text-green-500',
        'border-teal-500',
        'text-teal-600',
        'text-teal-500',
        'border-black-500',
        'text-black-600',
        'text-black-500',
    ],
};
