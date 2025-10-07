import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import defaultTheme from 'tailwindcss/defaultTheme';

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
                // Define 'sans' para que sea la fuente por defecto de todo el proyecto
                sans: ['"Exo 2"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'hydro-dark': '#073B3A',         // Fondo principal muy oscuro
                'hydro-card': '#0B6E4F',         // Fondo para tarjetas/tablas
                'hydro-accent-bright': '#08A045',// Verde vibrante para acentos
                'hydro-accent-light': '#6BBF59', // Verde claro para hover/iconos
                'hydro-accent-gold': '#DDB771',  // Dorado para botones principales
                'hydro-text-light': '#F0F2F5',    // Un blanco suave para texto
                'hydro-text-dark': '#073B3A',     // Para texto sobre fondos claros
            }

            // fontFamily: {
            //     sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            // },
        },
    },

    // plugins: [forms],
    plugins: [
        require('flowbite/plugin')
    ],
};
