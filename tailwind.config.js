import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import daisyui from 'daisyui';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito Sans', ...defaultTheme.fontFamily.sans],
                heading: ['Poppins', ...defaultTheme.fontFamily.sans],
                body: ['Nunito Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                navy: {
                    50: '#eef3ff',
                    100: '#dbe6ff',
                    200: '#bfd0ff',
                    300: '#95b1ff',
                    400: '#6787f5',
                    500: '#4565df',
                    600: '#324dc0',
                    700: '#1f3679',
                    800: '#182a5f',
                    900: '#14234c',
                },
                coral: {
                    50: '#fff2ef',
                    100: '#ffe3dc',
                    200: '#ffc6b8',
                    300: '#ffa08a',
                    400: '#ff7460',
                    500: '#f8553f',
                    600: '#e53f29',
                    700: '#bf2f20',
                    800: '#9e2920',
                    900: '#83281f',
                },
            },
        },
    },

    plugins: [forms, daisyui],
    daisyui: {
        themes: [
            {
                ppawarm: {
                    primary: '#1f3679',
                    secondary: '#f8553f',
                    accent: '#0d9488',
                    neutral: '#1f2937',
                    'base-100': '#ffffff',
                    'base-200': '#f8fafc',
                    'base-300': '#e2e8f0',
                    info: '#0284c7',
                    success: '#059669',
                    warning: '#d97706',
                    error: '#dc2626',
                },
            },
        ],
    },
};
