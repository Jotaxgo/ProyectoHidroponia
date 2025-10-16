@props(['active' => false])

<div class="relative flex items-center" x-data="{ open: false }" @click.away="open = false">
    @php
    $classes = ($active ?? false)
                ? 'active' // La clase 'active' activa la animación que ya definimos en app.css
                : '';
    @endphp

    <button @click="open = ! open" class="hydro-nav-link inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-400 hover:text-white focus:outline-none transition-colors duration-200 ease-in-out {{ $classes }}">
        <div>{{ $trigger }}</div>

        <div class="ms-1">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </div>
    </button>

    <div x-show="open"
         x-transition
         class="absolute z-50 top-full mt-2 w-48 rounded-md shadow-lg right-0"
         style="display: none;"
         @click="open = false">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 bg-hydro-dark border border-hydro-border py-1">
            {{ $content }}
        </div>
    </div>
</div>