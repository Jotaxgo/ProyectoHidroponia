@props(['active'])

@php
// La clase 'active' activa la animación de la barra inferior en nuestro CSS
$activeClasses = ($active ?? false) ? ' active' : '';

// Clases base para todos los enlaces del menú
$baseClasses = 'hydro-nav-link inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-400 hover:text-white focus:outline-none transition-colors duration-200 ease-in-out';

$classes = $baseClasses . $activeClasses;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
