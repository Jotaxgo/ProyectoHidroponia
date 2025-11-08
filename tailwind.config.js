// tailwind.config.js

import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
// (Eliminada la importación duplicada de defaultTheme)

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        // Asegúrate de que flowbite esté en tu content si lo usas mucho
        './node_modules/flowbite/**/*.js' 
    ],

    theme: {
        extend: {
            fontFamily: {
                // Mantenemos tu fuente 'Exo 2'
                sans: ['"Exo 2"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // =============================================
                // ¡AQUÍ ESTÁ TU NUEVA PALETA DE COLORES!
                // =============================================
                
                // Mapeamos los nuevos colores a tus nombres existentes:
                
                // Fondos (Reemplazando Verdes Oscuros)
                'hydro-dark': '#022600',        // (Nuevo: Muy Oscuro)
                'hydro-card': '#590004',        // (Nuevo: Rojo Oscuro/Maroon para sidebar, cards)
                'hydro-border': '#590004',      // (Nuevo: Borde igual que la tarjeta)

                // Acentos (Reemplazando Verdes Brillantes y Oro Antiguo)
                'hydro-accent-bright': '#A50104', // (Nuevo: Rojo Brillante para 'Crítico' o alertas)
                'hydro-accent-light': '#A50104',  // (Nuevo: Rojo Brillante)
                'hydro-accent-gold': '#FCBA04',   // (Nuevo: Amarillo/Oro para botones CTA)

                // Texto (Reemplazando Blanco Suave y Verde Oscuro)
                'hydro-text-light': '#590004',    // (Nuevo: Blanco Suave/Gris Claro)
                'hydro-text-dark': '#022600',     // (Nuevo: Negro/Oscuro para texto en fondos claros)
                
                // Hover (Mantenemos blanco, es un buen contraste)
                'hydro-hover': '#FFFFFF',
            }
        },
    },

    plugins: [
        forms, // Mantenemos el plugin de forms si lo usas
        require('flowbite/plugin') // Mantenemos tu plugin de flowbite
    ],
};