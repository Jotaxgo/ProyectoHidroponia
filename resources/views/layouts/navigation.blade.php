@php
    $userRole = Auth::user()->role->nombre_rol ?? 'user';
    $isAdmin = $userRole === 'Admin';
    $isDueñoVivero = $userRole === 'Dueño de Vivero';
@endphp

<!-- DOCK NAVIGATION - macOS STYLE WITH LABELS -->

<!-- Dashboard -->
<a href="{{ route('dashboard') }}" 
   class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
   {{ request()->routeIs('dashboard') ? 'aria-current="page"' : '' }}>
    <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1V9a1 1 0 011-1h2a1 1 0 011 1v10a1 1 0 001 1h2a1 1 0 001-1V9a1 1 0 00-1-1H9a1 1 0 00-1 1v10z"></path>
    </svg>
    <span class="nav-label">Dashboard</span>
</a>

<!-- ADMIN SECTION -->
@if($isAdmin)
    <!-- USUARIOS -->
    <a href="{{ route('admin.users.index') }}" 
       class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
       {{ request()->routeIs('admin.users.*') ? 'aria-current="page"' : '' }}>
        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        <span class="nav-label">Usuarios</span>
    </a>

    <!-- VIVEROS -->
    <a href="{{ route('admin.viveros.index') }}" 
       class="nav-item {{ request()->routeIs('admin.viveros.*') ? 'active' : '' }}"
       {{ request()->routeIs('admin.viveros.*') ? 'aria-current="page"' : '' }}>
        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-1 4h1m6-4h1m-1 4h1m-1 4h1M9 3v1m6-1v1"></path>
        </svg>
        <span class="nav-label">Viveros</span>
    </a>

    <!-- MÓDULOS -->
    <a href="{{ route('admin.modulos.indexAll') }}" 
       class="nav-item {{ request()->routeIs('admin.modulos.*') ? 'active' : '' }}"
       {{ request()->routeIs('admin.modulos.*') ? 'aria-current="page"' : '' }}>
        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
        </svg>
        <span class="nav-label">Módulos</span>
    </a>

    <!-- SEPARADOR -->
    <div class="nav-section-divider"></div>
@endif

<!-- REPORTES SECTION -->
@if ($isAdmin || $isDueñoVivero)
    <!-- REPORTE DE MÓDULO -->
    <a href="{{ route('admin.reportes.module.form') }}" 
       class="nav-item {{ request()->routeIs('admin.reportes.module.*') ? 'active' : '' }}"
       {{ request()->routeIs('admin.reportes.module.*') ? 'aria-current="page"' : '' }}>
        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
        <span class="nav-label">Rep. Módulo</span>
    </a>

    @if($isAdmin)
        <!-- REPORTE DE VIVEROS -->
        <a href="{{ route('admin.reportes.viveros.show') }}" 
           class="nav-item {{ request()->routeIs('admin.reportes.viveros.*') ? 'active' : '' }}"
           {{ request()->routeIs('admin.reportes.viveros.*') ? 'aria-current="page"' : '' }}>
            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
            </svg>
            <span class="nav-label">Rep. Viveros</span>
        </a>
    @endif

    <!-- SEPARADOR -->
    <div class="nav-section-divider"></div>
@endif

<!-- PROFILE SECTION - BOTTOM -->
<div style="margin-top: auto; padding-top: 12px; border-top: 1px solid rgba(156, 0, 0, 0.1);">
    <!-- PROFILE -->
    <a href="{{ route('profile.edit') }}" 
       class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}"
       {{ request()->routeIs('profile.*') ? 'aria-current="page"' : '' }}>
        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="nav-label">Perfil</span>
    </a>

    <!-- LOGOUT -->
    <form method="POST" action="{{ route('logout') }}" style="display: contents;">
        @csrf
        <button type="submit" class="nav-item" style="background: none; border: none; cursor: pointer;">
            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            <span class="nav-label">Salir</span>
        </button>
    </form>
</div>