@props(['active'])

@php
    // La clase 'active' ahora solo se usa para activar la animación, no para cambiar colores aquí
    $baseClasses = 'hydro-nav-link inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-hydro-light hover:text-hydro-hover focus:outline-none transition duration-150 ease-in-out';
    $activeClasses = ($active ?? false) ? ' active' : '';
    $classes = $baseClasses . $activeClasses;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
