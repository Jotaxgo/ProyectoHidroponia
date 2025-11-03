<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Hidrofrutilla') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts (Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- SCRIPT Y ESTILO "ANTI-FLICKER" --}}
        <script>
            try {
                if (JSON.parse(localStorage.getItem('isSidebarOpen') || 'false')) {
                    document.documentElement.classList.add('sidebar-open');
                } else {
                    document.documentElement.classList.remove('sidebar-open');
                }
            } catch (e) { localStorage.setItem('isSidebarOpen', 'false'); }
        </script>
        <style>
            [x-cloak] { display: none !important; }
        </style>
        {{-- FIN ANTI-FLICKER --}}

    </head>
    <body class="font-sans antialiased">
        
        <div 
            x-data="{ 
                isSidebarOpen: JSON.parse(localStorage.getItem('isSidebarOpen') || 'false'),
                toggleSidebar() {
                    this.isSidebarOpen = !this.isSidebarOpen;
                    localStorage.setItem('isSidebarOpen', JSON.stringify(this.isSidebarOpen));
                    document.documentElement.classList.toggle('sidebar-open', this.isSidebarOpen);
                }
            }" 
            class="min-h-screen flex bg-gray-100 dark:bg-gray-900"
        >
            
            {{-- 1. LA BARRA LATERAL (SIDEBAR) --}}
            <aside 
                class="sidebar fixed inset-y-0 left-0 z-20 bg-hydro-card text-hydro-text-light 
                       transform transition-all duration-300 ease-in-out
                       overflow-x-hidden" 
                x-cloak
            >
                @include('layouts.navigation')
            </aside>

            {{-- 2. EL ÁREA DE CONTENIDO PRINCIPAL --}}
            <div 
                class="main-content flex flex-col flex-1 transition-all duration-300 ease-in-out"
            >

                <!-- Encabezado Superior (Header) -->
                {{-- 
                    ======================================================
                    MODIFICACIÓN 1: 
                    Cambiamos h-16 por h-[170px] (150px del logo + 20px de padding)
                    ======================================================
                --}}
                <header class="bg-white dark:bg-hydro-card shadow-md h-[130px] flex-shrink-0">
                    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                        {{-- 
                            ======================================================
                            MODIFICACIÓN 2: 
                            Cambiamos h-16 por h-full para que ocupe los 170px
                            ======================================================
                        --}}
                        <div class="flex justify-between items-center h-full">
                            
                            <!-- Lado Izquierdo: Botón Hamburguesa -->
                            <div class="flex items-center">
                                <button 
                                    @click="toggleSidebar()" 
                                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 transition"
                                >
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Centro: Logo y Nombre (Centrado) -->
                            <div class="flex-grow flex justify-center"> 
                                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                                    {{-- 
                                        NO SE TOCA EL TAMAÑO DEL LOGO (h-[150px])
                                    --}}
                                    <img src="{{ asset('images/Logo Hidrofrutilla 2.png') }}" alt="Logo" class="block h-[150px] w-auto"> 
                                    <span class="font-bold text-2xl text-white tracking-wider hidden md:block">
                                        Hidrofrutilla
                                    </span>
                                </a>
                            </div>

                            {{-- 
                                Lado Derecho: 
                                (Eliminado, tal como en tu código)
                            --}}
                            
                        </div>
                    </div>
                </header>

                <!-- Encabezado de la Página (El título) -->
                @if (isset($header))
                    <header class="bg-white dark:bg-hydro-card shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Contenido Principal (El Slot) -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>