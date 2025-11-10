<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hidrofrutilla') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dockVisible = JSON.parse(localStorage.getItem('dockVisible') || 'true');
            if (!dockVisible) {
                document.documentElement.classList.add('dock-hidden');
            }
        });
    </script>

    <style>
        [x-cloak] { display: none !important; }
        :root {
            --strawberry-dark: #9c0000;
            --strawberry: #ff4b65;
            --strawberry-light: #ffdef0;
            --leaf-green: #96d900;
            --bg-light: #ffffff;
            --border: #e0e0e0;
            --text-dark: #1a1a1a;
            --text-muted: #555555;
            --shadow-sm: 0 4px 15px rgba(156, 0, 0, 0.05);
            --shadow-md: 0 10px 30px rgba(156, 0, 0, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f5f5f5;
            font-family: 'Inter', sans-serif;
        }

        .main-layout {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* SIDEBAR - macOS DOCK STYLE */
        .sidebar {
            position: fixed;
            left: 0;
            bottom: 0;
            top: 100px;
            width: 90px;
            background: rgba(250, 250, 250, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(224, 224, 224, 0.5);
            box-shadow: inset -1px 0 0 rgba(156, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px 0;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 100;
        }

        /* Scrollbar personalizado */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(156, 0, 0, 0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 0, 0, 0.3);
        }

        .logo-section {
            display: none;
        }

        .collapse-btn {
            display: none;
        }

        /* NAVEGACIÓN - DOCK STYLE */
        .sidebar nav {
            padding: 0;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            width: 100%;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0;
            text-decoration: none;
            color: var(--text-dark);
            border-radius: 16px;
            margin: 0;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            white-space: nowrap;
            position: relative;
            width: 76px;
            height: auto;
            background: transparent;
            min-height: 80px;
        }

        .nav-item:hover {
            background: rgba(255, 75, 101, 0.15);
            transform: scale(1.1);
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(156, 0, 0, 0.25), rgba(255, 75, 101, 0.15));
            box-shadow: 0 8px 24px rgba(156, 0, 0, 0.15);
        }

        .sidebar-icon {
            width: 32px;
            height: 32px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-muted);
            text-align: center;
            line-height: 1.2;
            max-width: 70px;
            word-break: break-word;
            transition: color 0.2s ease;
        }

        .nav-item:hover .nav-label {
            color: var(--strawberry-dark);
            font-weight: 700;
        }

        .nav-item.active .nav-label {
            color: var(--strawberry);
            font-weight: 700;
        }

        /* SEPARADOR VISUAL EN DOCK */
        .nav-section-divider {
            width: 40px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(156, 0, 0, 0.2), transparent);
            margin: 4px 0;
        }

        /* HEADER */
        .header {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(224, 224, 224, 0.4);
            box-shadow: 0 4px 20px rgba(156, 0, 0, 0.06);
            height: 100px;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            position: relative;
            gap: 24px;
            margin-left: 0;
        }

        .header-left {
            flex: 0.5;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-title {
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--strawberry-dark), var(--strawberry));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            white-space: nowrap;
            letter-spacing: -0.5px;
        }

        .header-title-icon {
            font-size: 24px;
            display: inline-block;
        }

        .header-center {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .header-logo {
            height: 75px;
            object-fit: contain;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            filter: drop-shadow(0 4px 12px rgba(156, 0, 0, 0.1));
        }

        .header-logo:hover {
            transform: scale(1.08);
            filter: drop-shadow(0 8px 24px rgba(156, 0, 0, 0.15));
        }

        .header-right {
            flex: 0.5;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 16px;
        }

        .header-user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: rgba(156, 0, 0, 0.05);
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .header-user-info:hover {
            background: rgba(156, 0, 0, 0.1);
        }

        .header-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--strawberry), var(--strawberry-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(156, 0, 0, 0.15);
        }

        .header-user-details {
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 0;
        }

        .header-user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .header-user-email {
            font-size: 11px;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .header-user strong {
            color: var(--text-dark);
            font-weight: 600;
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .logout-btn {
            width: 40px;
            height: 40px;
            background: rgba(156, 0, 0, 0.1);
            color: var(--strawberry);
            border: 1px solid rgba(156, 0, 0, 0.2);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-weight: 600;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .logout-btn:hover {
            background: rgba(156, 0, 0, 0.15);
            border-color: rgba(156, 0, 0, 0.3);
            transform: scale(1.08);
        }

        .logout-btn:active {
            transform: scale(0.95);
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .content-area {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
        }

        .page-header {
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-dark);
            margin: 0;
        }

        .content-wrapper {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* SCROLLBAR PERSONALIZADO */
        .sidebar::-webkit-scrollbar,
        .content-area::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar::-webkit-scrollbar-track,
        .content-area::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb,
        .content-area::-webkit-scrollbar-thumb {
            background: rgba(156, 0, 0, 0.2);
            border-radius: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover,
        .content-area::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 0, 0, 0.4);
        }

        /* FOOTER */
        .footer {
            background: white;
            border-top: 1px solid var(--border);
            padding: 16px 24px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .footer strong {
            color: var(--strawberry);
            font-weight: 700;
        }
        .notification-btn {
            position: relative;
            width: 40px;
            height: 40px;
            background: rgba(255, 75, 101, 0.1);
            color: var(--strawberry);
            border: 1px solid rgba(255, 75, 101, 0.2);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .notification-btn:hover {
            background: rgba(255, 75, 101, 0.15);
            border-color: rgba(255, 75, 101, 0.3);
            transform: scale(1.08);
        }
        .notification-btn:active {
            transform: scale(0.95);
        }
        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--strawberry-dark);
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: 700;
            line-height: 1;
            min-width: 20px;
            text-align: center;
        }
        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            width: 300px;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
            z-index: 1000;
            overflow: hidden;
        }
        .notification-header {
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .notification-header .mark-all-read {
            font-size: 12px;
            color: var(--strawberry);
            text-decoration: none;
        }
        .notification-item {
            display: block;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            text-decoration: none;
            color: var(--text-dark);
            transition: background-color 0.2s ease;
        }
        .notification-item:hover {
            background-color: #f9f9f9;
        }
        .notification-content {
            font-size: 13px;
            line-height: 1.4;
            margin-bottom: 4px;
        }
        .notification-time {
            font-size: 11px;
            color: var(--text-muted);
        }
        .notification-empty {
            padding: 16px;
            text-align: center;
            color: var(--text-muted);
            font-size: 13px;
        }
        .notification-footer {
            padding: 12px 16px;
            text-align: center;
            border-top: 1px solid var(--border);
        }
        .notification-footer a {
            color: var(--strawberry);
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <div class="main-layout" x-data="{ dockVisible: JSON.parse(localStorage.getItem('dockVisible') || 'true') }">
        
        <!-- SIDEBAR - macOS DOCK -->
        <aside class="sidebar" x-cloak>
            <!-- NAVEGACIÓN - DOCK STYLE -->
            <nav>
                @include('layouts.navigation')
            </nav>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="main-content">
            
            <!-- HEADER -->
            <header class="header">
                <!-- LEFT: TÍTULO -->
                <div class="header-left">
                    <span class="header-title-icon">🌱</span>
                    <h1 class="header-title">Hidrofrutilla</h1>
                </div>
                
                <!-- CENTER: LOGO -->
                <a href="{{ route('dashboard') }}" class="header-center">
                    <img src="{{ asset('images/Logo Hidrofrutilla 2.png') }}" alt="Hidrofrutilla" class="header-logo">
                </a>
                
                <!-- RIGHT: USER INFO + LOGOUT -->
                <div class="header-right">
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open" class="notification-btn">
                            <span class="text-2xl">🔔</span>
                            @if($notifications->count() > 0)
                                <span class="notification-count">{{ $notifications->count() }}</span>
                            @endif
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="notification-dropdown">
                            <div class="notification-header">
                                Notificaciones
                                <a href="{{ route('notifications.markAllAsRead') }}" class="mark-all-read">Marcar todas como leídas</a>
                            </div>
                            @forelse($notifications as $notification)
                                <a href="{{ route('notifications.show', $notification->id) }}" class="notification-item">
                                    <div class="notification-content">
                                        {{ $notification->data['messages'][0] ?? 'Nueva alerta' }}
                                    </div>
                                    <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                                </a>
                            @empty
                                <div class="notification-empty">No hay notificaciones nuevas.</div>
                            @endforelse
                            <div class="notification-footer">
                                <a href="{{ route('notifications.index') }}">Ver todas</a>
                            </div>
                        </div>
                    </div>
                    <div class="header-user-info">
                        <div class="header-user-avatar">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="header-user-details">
                            <div class="header-user-name">{{ Auth::user()->name }}</div>
                            <div class="header-user-email">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn" title="Cerrar Sesión">
                            🚪
                        </button>
                    </form>
                </div>
            </header>

            <!-- CONTENIDO -->
            <div class="content-area">
                @if (isset($header))
                    <div class="page-header">
                        <h2 class="page-title">{{ $header }}</h2>
                    </div>
                @endif
                <div class="content-wrapper">
                    {{ $slot }}
                </div>
            </div>

            <!-- FOOTER -->
            <footer class="footer">
                © {{ date('Y') }} <strong>Hidrofrutilla</strong> - Sistema Hidropónico
            </footer>

        </div>

    </div>

</body>
</html>
