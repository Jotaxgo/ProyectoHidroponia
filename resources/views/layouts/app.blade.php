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
            
            {{-- ============================================= --}}
            {{-- 1. LA BARRA LATERAL (SIDEBAR) --}}
            {{-- ============================================= --}}
            
            <!-- Fondo oscuro para overlay en móvil (cuando se abre) -->
            {{-- 
                AÑADIDO: md:hidden para que SÓLO aparezca en móviles
                z-index (z-20) debe ser menor que el sidebar (z-30)
            --}}
            <div 
                @click="toggleSidebar()" 
                class="fixed inset-0 z-20 bg-black opacity-50 transition-opacity md:hidden" 
                x-show="isSidebarOpen" 
                x-cloak
            ></div>

            <!-- El Sidebar (Contenido del Menú) -->
            <aside 
                class="sidebar fixed inset-y-0 left-0 z-30 bg-hydro-card text-hydro-text-light 
                       transform transition-all duration-300 ease-in-out
                       overflow-x-hidden" 
                x-cloak
            >
                {{-- Cargamos el menú (navigation.blade.php - que ya está bien) --}}
                @include('layouts.navigation')
            </aside>

            {{-- ============================================= --}}
            {{-- 2. EL ÁREA DE CONTENIDO PRINCIPAL --}}
            {{-- ============================================= --}}
            <div 
                class="main-content flex flex-col flex-1 transition-all duration-300 ease-in-out"
            >

                <!-- Encabezado Superior (Header) -->
                <header class="bg-white dark:bg-hydro-card shadow-md h-16 flex-shrink-0">
                    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between items-center h-16">
                            
                            <!-- Lado Izquierdo: Botón Hamburguesa Y Logo/Nombre -->
                            <div class="flex items-center">
                                <!-- Botón Hamburguesa -->
                                <button 
                                    @click="toggleSidebar()" 
                                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 transition"
                                >
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </button>
                                <!-- Logo y Nombre (Fijos en el Header) -->
                                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 ml-4">
                                    <img src="{{ asset('images/mi-logo.png') }}" alt="Logo" class="block h-10 w-auto"> 
                                    <span class="font-bold text-xl text-white tracking-wider hidden md:block">
                                        Hidrofrutilla
                                    </span>
                                </a>
                            </div>

                            
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