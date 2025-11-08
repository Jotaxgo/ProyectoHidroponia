<div class="flex flex-col h-full p-6 text-[#1a1a1a]">

    {{-- BOTÓN CERRAR (MÓVIL) --}}
    <div class="flex justify-end mb-6 md:hidden" x-show="isSidebarOpen" x-transition x-cloak>
        <button @click="toggleSidebar()" 
                class="p-3 rounded-full bg-[#ff4b65] text-white shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-200"
                aria-label="Cerrar navegación">
            <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- NAVEGACIÓN --}}
    <nav class="flex-1 space-y-2 overflow-y-hidden">

        @php
            $userRole = Auth::user()->role->nombre_rol;
        @endphp

        {{-- DASHBOARD --}}
        <a href="{{ route('dashboard') }}"
           title="{{ __('Dashboard') }}"
           class="flex items-center p-4 rounded-2xl transition-all group
                  {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white shadow-lg' : 'text-[#555555] hover:bg-[#ffdef0]/50' }}"
           :class="!isSidebarOpen ? 'justify-center' : ''"
           {{ request()->routeIs('dashboard') ? 'aria-current="page"' : '' }}
        >
            <div class="w-12 h-12 rounded-xl flex items-center justify-center
                        {{ request()->routeIs('dashboard') ? 'bg-white/20' : 'bg-[#e0e0e0] group-hover:bg-white/70' }} transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1V9a1 1 0 011-1h2a1 1 0 011 1v10a1 1 0 001 1h2a1 1 0 001-1V9a1 1 0 00-1-1H9a1 1 0 00-1 1v10z"></path>
                </svg>
            </div>
            <span class="ml-4 font-semibold">{{ __('Dashboard') }}</span>
        </a>

        {{-- ADMIN --}}
        @if($userRole == 'Admin')
            <div class="mt-6">
                <p class="text-xs font-bold text-[#555555] uppercase tracking-widest mb-3 px-4">Administración</p>

                {{-- USUARIOS --}}
                @if(Route::has('admin.users.index'))
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center p-4 rounded-2xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white shadow-lg' : 'text-[#555555] hover:bg-[#ffdef0]/50' }}"
                   :class="!isSidebarOpen ? 'justify-center' : ''"
                   {{ request()->routeIs('admin.users.*') ? 'aria-current="page"' : '' }}
                >
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#e0e0e0] {{ request()->routeIs('admin.users.*') ? 'bg-white/20' : '' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <span class="ml-4">{{ __('Gestión de Usuarios') }}</span>
                </a>
                @endif

                {{-- VIVEROS --}}
                @if(Route::has('admin.viveros.index'))
                <a href="{{ route('admin.viveros.index') }}" 
                   class="flex items-center p-4 rounded-2xl transition-all {{ request()->routeIs('admin.viveros.*') ? 'bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white shadow-lg' : 'text-[#555555] hover:bg-[#ffdef0]/50' }}"
                   :class="!isSidebarOpen ? 'justify-center' : ''"
                   {{ request()->routeIs('admin.viveros.*') ? 'aria-current="page"' : '' }}
                >
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#e0e0e0] {{ request()->routeIs('admin.viveros.*') ? 'bg-white/20' : '' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-1 4h1m6-4h1m-1 4h1m-1 4h1M9 3v1m6-1v1"></path>
                        </svg>
                    </div>
                    <span class="ml-4">{{ __('Gestión de Viveros') }}</span>
                </a>
                @endif

                {{-- MÓDULOS --}}
                @if(Route::has('admin.modulos.indexAll'))
                <a href="{{ route('admin.modulos.indexAll') }}" 
                   class="flex items-center p-4 rounded-2xl transition-all {{ request()->routeIs('admin.modulos.indexAll') ? 'bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white shadow-lg' : 'text-[#555555] hover:bg-[#ffdef0]/50' }}"
                   :class="!isSidebarOpen ? 'justify-center' : ''"
                   {{ request()->routeIs('admin.modulos.indexAll') ? 'aria-current="page"' : '' }}
                >
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#e0e0e0] {{ request()->routeIs('admin.modulos.indexAll') ? 'bg-white/20' : '' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    <span class="ml-4">{{ __('Inventario de Módulos') }}</span>
                </a>
                @endif
            </div>
        @endif

        {{-- REPORTES --}}
        @if ($userRole === 'Admin' || $userRole === 'Dueño de Vivero')
            <div class="mt-6">
                <p class="text-xs font-bold text-[#555555] uppercase tracking-widest mb-3 px-4">Reportes</p>

                {{-- REPORTE DE MÓDULO --}}
                @if(Route::has('admin.reportes.module.form'))
                <a href="{{ route('admin.reportes.module.form') }}" 
                   class="flex items-center p-4 rounded-2xl transition-all {{ request()->routeIs('admin.reportes.module.form') ? 'bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white shadow-lg' : 'text-[#555555] hover:bg-[#ffdef0]/50' }}"
                   :class="!isSidebarOpen ? 'justify-center' : ''"
                   {{ request()->routeIs('admin.reportes.module.form') ? 'aria-current="page"' : '' }}
                >
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#e0e0e0] {{ request()->routeIs('admin.reportes.module.form') ? 'bg-white/20' : '' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="ml-4">{{ __('Reporte de Módulo') }}</span>
                </a>
                @endif

                {{-- REPORTE DE VIVEROS (solo Admin) --}}
                @if ($userRole === 'Admin' && Route::has('admin.reportes.viveros.show'))
                <a href="{{ route('admin.reportes.viveros.show') }}" 
                   class="flex items-center p-4 rounded-2xl transition-all {{ request()->routeIs('admin.reportes.viveros.show') ? 'bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white shadow-lg' : 'text-[#555555] hover:bg-[#ffdef0]/50' }}"
                   :class="!isSidebarOpen ? 'justify-center' : ''"
                   {{ request()->routeIs('admin.reportes.viveros.show') ? 'aria-current="page"' : '' }}
                >
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#e0e0e0] {{ request()->routeIs('admin.reportes.viveros.show') ? 'bg-white/20' : '' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                    </div>
                    <span class="ml-4">{{ __('Reporte de Viveros') }}</span>
                </a>
                @endif
            </div>
        @endif

    </nav>

    {{-- PERFIL + LOGOUT --}}
    <div class="mt-auto pt-6 border-t border-[#e0e0e0]">
        <a href="{{ route('profile.edit') }}"
           class="flex items-center p-4 rounded-2xl transition-all {{ request()->routeIs('profile.edit') ? 'bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white shadow-lg' : 'text-[#555555] hover:bg-[#ffdef0]/50' }}"
           :class="!isSidebarOpen ? 'justify-center' : ''"
           {{ request()->routeIs('profile.edit') ? 'aria-current="page"' : '' }}
        >
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#ff4b65] to-[#9c0000] p-0.5">
                <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                    <span class="text-sm font-bold text-[#ff4b65]">
                        {{ substr(Auth::user()->full_name ?? 'U', 0, 1) }}
                    </span>
                </div>
            </div>
            <div class="ml-4">
                <div class="font-semibold">{{ Auth::user()->full_name }}</div>
                <div class="text-xs text-[#555555]">{{ Auth::user()->email }}</div>
            </div>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit"
                    class="w-full flex items-center p-4 rounded-2xl text-[#ff4b65] hover:bg-red-50 transition"
                    :class="!isSidebarOpen ? 'justify-center' : ''"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span class="ml-4">{{ __('Cerrar Sesión') }}</span>
            </button>
        </form>
    </div>
</div>

<!-- ESTILOS LOCALES (para mantener hydro-* y mejorar visualmente) -->
<style>
    .bg-hydro-dark { background: linear-gradient(135deg, #ffffff, rgba(255, 222, 243, 0.3)) !important; }
    .text-hydro-text-light { color: #1a1a1a !important; }
    .border-hydro-dark { border-color: #e0e0e0 !important; }
</style>