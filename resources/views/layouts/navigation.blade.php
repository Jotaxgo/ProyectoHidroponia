{{-- resources/views/layouts/navigation.blade.php (Modificado) --}}
<nav x-data="{ open: false }" class="bg-hydro-card border-b border-hydro-dark">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        {{-- <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" /> --}}
                        <h1 class="text-xl font-bold text-hydro-accent-gold">HIDROFRUTILLA</h1>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="hydro-nav-link">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- === ENLACES SOLO PARA ADMIN === --}}
                    @if(Auth::user()->role->nombre_rol == 'Admin')
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="hydro-nav-link">
                            {{ __('Gestión de Usuarios') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.viveros.index')" :active="request()->routeIs('admin.viveros.*')" class="hydro-nav-link">
                            {{ __('Gestión de Viveros') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.modulos.indexAll')" :active="request()->routeIs('admin.modulos.indexAll')" class="hydro-nav-link">
                            {{ __('Inventario de Módulos') }}
                        </x-nav-link>
                    @endif

                    {{-- === ENLACE PARA REPORTES (ADMIN Y DUEÑO) === --}}
                    @php
                        $userRole = Auth::user()->role->nombre_rol;
                    @endphp
                    @if ($userRole === 'Admin' || $userRole === 'Dueño de Vivero')
                        <x-nav-dropdown-link :active="request()->routeIs('admin.reportes.*')">
                            <x-slot name="trigger">
                                {{ __('Reportes') }}
                            </x-slot>
                            <x-slot name="content">
                                <a href="{{ route('admin.reportes.module.form') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-hydro-text-light hover:bg-hydro-dark transition duration-150 ease-in-out">
                                    Reporte de Módulo
                                </a>
                                {{-- Solo el Admin ve el reporte de Viveros --}}
                                @if ($userRole === 'Admin')
                                <a href="{{ route('admin.reportes.viveros.show') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-hydro-text-light hover:bg-hydro-dark transition duration-150 ease-in-out">
                                    Reporte de Viveros
                                </a>
                                @endif
                            </x-slot>
                        </x-nav-dropdown-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-400 bg-hydro-card hover:text-gray-300 focus:outline-none transition">
                            <div>{{ Auth::user()->full_name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>
                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-900 focus:outline-none focus:bg-gray-900 focus:text-gray-400 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        {{-- === SECCIÓN DE ADMINISTRACIÓN (MÓVIL) === --}}
        @php
            $userRole = Auth::user()->role->nombre_rol; // Obtenemos el rol de nuevo para la parte responsive
        @endphp

        {{-- Enlaces solo para Admin (Móvil) --}}
        @if($userRole == 'Admin')
            <div class="pt-4 pb-1 border-t border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-400">Administración</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('Gestión de Usuarios') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.viveros.index')" :active="request()->routeIs('admin.viveros.*')">
                        {{ __('Gestión de Viveros') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.modulos.indexAll')" :active="request()->routeIs('admin.modulos.indexAll')">
                        {{ __('Inventario de Módulos') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @endif

        {{-- === SECCIÓN DE REPORTES (MÓVIL - ADMIN Y DUEÑO) === --}}
        @if ($userRole === 'Admin' || $userRole === 'Dueño de Vivero')
            <div class="pt-4 pb-1 border-t border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-400">Reportes</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('admin.reportes.module.form')" :active="request()->routeIs('admin.reportes.module.form')">
                        Reporte de Módulo
                    </x-responsive-nav-link>
                    {{-- Solo Admin ve reporte de Viveros (Móvil) --}}
                    @if ($userRole === 'Admin')
                    <x-responsive-nav-link :href="route('admin.reportes.viveros.show')" :active="request()->routeIs('admin.reportes.viveros.show')">
                        Reporte de Viveros
                    </x-responsive-nav-link>
                    @endif
                </div>
            </div>
        @endif

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-200">{{ Auth::user()->full_name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>
                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>