@props(['active'])

@props(['active'])

@php
$classes = ($active ?? false)
            // Estilos para el enlace ACTIVO
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-hydro-accent-gold text-start text-base font-medium text-hydro-dark bg-hydro-accent-gold focus:outline-none transition duration-150 ease-in-out'
            // Estilos para los enlaces INACTIVOS
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-hydro-text-light hover:text-hydro-hover hover:bg-hydro-dark hover:border-gray-600 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
