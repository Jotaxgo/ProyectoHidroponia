{{-- resources/views/layouts/navigation.blade.php (NUEVO DISEÑO DE ICONOS) --}}

<div class="flex flex-col h-full text-hydro-text-light">

{{-- 1. Botón de Cerrar (Solo visible si está abierto) --}}
    {{-- ====================================================== --}}
    <div 
        class="h-16 flex-shrink-0 flex items-center justify-end px-4 border-b border-hydro-dark/50"
        {{-- 
            Este bloque (y el botón) solo se muestra si el menú está ABIERTO.
            Cuando está colapsado (solo iconos), no es necesario.
        --}}
        x-show="isSidebarOpen" x-transition x-cloak
    >
        <button 
            @click="toggleSidebar()" {{-- Llama a la misma función que el botón hamburguesa --}}
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 transition"
        >
            <span class="sr-only">Cerrar menú</span>
            {{-- Icono 'X' para cerrar --}}
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    {{-- ====================================================== --}}
    
    <!-- 1. Enlaces de Navegación (Verticales) -->
    <nav 
        class="flex-1 pt-6 space-y-2 px-3" 
        {{-- CORRECCIÓN: Quitamos el scrollbar para SIEMPRE --}}
        overflow-y-hidden
        
    >
        
        @php
            $userRole = Auth::user()->role->nombre_rol; 
        @endphp

        {{-- Enlace Dashboard (Icono SIEMPRE visible) --}}
        <a href="{{ route('dashboard') }}" 
           title="{{ __('Dashboard') }}"
           class="flex items-center p-2 rounded-md transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}"
           :class="!isSidebarOpen ? 'justify-center' : ''"
        >
            <svg class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1V9a1 1 0 011-1h2a1 1 0 011 1v10a1 1 0 001 1h2a1 1 0 001-1V9a1 1 0 00-1-1H9a1 1 0 00-1 1v10z"></path></svg>
            <span class="ml-4 whitespace-nowrap" x-show="isSidebarOpen" x-transition x-cloak>{{ __('Dashboard') }}</span>
        </a>

        {{-- Enlaces solo para Admin --}}
        @if($userRole == 'Admin')
            {{-- MODIFICACIÓN: Todo este bloque se OCULTA si el menú está colapsado --}}
            <div class="pt-4 pb-1 border-t border-gray-700" x-show="isSidebarOpen" x-transition x-cloak>
                <div class="px-2">
                    <span class="font-medium text-base text-gray-400">Administración</span>
                </div>
                <div class="mt-3 space-y-1">
                    <a href="{{ route('admin.users.index') }}" title="{{ __('Gestión de Usuarios') }}" class="flex items-center p-2 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="ml-4 whitespace-nowrap">{{ __('Gestión de Usuarios') }}</span>
                    </a>
                    <a href="{{ route('admin.viveros.index') }}" title="{{ __('Gestión de Viveros') }}" class="flex items-center p-2 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.viveros.*') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-1 4h1m6-4h1m-1 4h1m-1 4h1M9 3v1m6-1v1"></path></svg>
                        <span class="ml-4 whitespace-nowrap">{{ __('Gestión de Viveros') }}</span>
                    </a>
                    <a href="{{ route('admin.modulos.indexAll') }}" title="{{ __('Inventario de Módulos') }}" class="flex items-center p-2 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.modulos.indexAll') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        <span class="ml-4 whitespace-nowrap">{{ __('Inventario de Módulos') }}</span>
                    </a>
                </div>
            </div>
        @endif

        {{-- Enlaces para Reportes (Admin y Dueño) --}}
        @if ($userRole === 'Admin' || $userRole === 'Dueño de Vivero')
            {{-- MODIFICACIÓN: Todo este bloque se OCULTA si el menú está colapsado --}}
            <div class="pt-4 pb-1 border-t border-gray-700" x-show="isSidebarOpen" x-transition x-cloak>
                <div class="px-2">
                    <span class="font-medium text-base text-gray-400">Reportes</span>
                </div>
                <div class="mt-3 space-y-1">
                    {{-- Reporte de Módulo --}}
                    <a href="{{ route('admin.reportes.module.form') }}" title="Reporte de Módulo" class="flex items-center p-2 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.reportes.module.form') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="ml-4 whitespace-nowrap">{{ __('Reporte de Módulo') }}</span>
                    </a>
                    @if ($userRole === 'Admin')
                    {{-- Reporte de Viveros --}}
                    <a href="{{ route('admin.reportes.viveros.show') }}" title="Reporte de Viveros" class="flex items-center p-2 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.reportes.viveros.show') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        <span class="ml-4 whitespace-nowrap">{{ __('Reporte de Viveros') }}</span>
                    </a>
                    @endif
                </div>
            </div>
        @endif
    </nav>
    
    <!-- 3. Sección de Perfil (Inferior) -->
    {{-- Iconos SIEMPRE visibles --}}
    <div 
        class="pt-4 pb-4 border-t border-gray-700 flex-shrink-0 px-3" 
    >
        {{-- Enlace a Perfil --}}
        <a href="{{ route('profile.edit') }}" 
           class="flex items-center p-2 rounded-md transition-colors duration-200 {{ request()->routeIs('profile.edit') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}"
           title="Perfil y API Keys"
           :class="!isSidebarOpen ? 'justify-center' : ''"
        >
            <svg class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <div class="ml-4 overflow-hidden whitespace-nowrap" x-show="isSidebarOpen" x-transition x-cloak>
                <div class="font-medium text-base text-gray-200">{{ Auth::user()->full_name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
        </a>

        {{-- Formulario de Cerrar Sesión --}}
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); this.closest('form').submit();"
               class="flex items-center p-2 rounded-md transition-colors duration-200 text-gray-400 hover:bg-red-800 hover:text-white"
               title="Cerrar Sesión"
               :class="!isSidebarOpen ? 'justify-center' : ''">
                
                <svg class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span class="ml-4 whitespace-nowrap" x-show="isSidebarOpen" x-transition x-cloak>{{ __('Cerrar Sesión') }}</span>
            </a>
        </form>
    </div>
</div>