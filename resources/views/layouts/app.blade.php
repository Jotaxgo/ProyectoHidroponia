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
            const isOpen = JSON.parse(localStorage.getItem('isSidebarOpen') || 'true');
            document.documentElement.classList.toggle('sidebar-open', isOpen);
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
            --active-bg: linear-gradient(135deg, var(--strawberry-dark), var(--strawberry));
        }

        .sidebar-glass {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-right: 1px solid var(--border);
            box-shadow: var(--shadow-md);
            width: 280px;
        }

        .header-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .page-title {
            background: var(--active-bg);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
        }

        /* BOTÓN HAMBURGUESA ANIMADO */
        .btn-hamburger {
            position: relative;
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--active-bg);
            color: white;
            box-shadow: 0 8px 25px rgba(156, 0, 0, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        .btn-hamburger:hover {
            transform: scale(1.08);
            box-shadow: 0 12px 30px rgba(156, 0, 0, 0.4);
        }
        .btn-hamburger:active {
            transform: scale(0.95);
        }

        /* LÍNEAS ANIMADAS */
        .hamburger-line {
            position: absolute;
            height: 0.125rem;
            width: 1.5rem;
            background: white;
            border-radius: 9999px;
            transition: all 300ms;
        }
        .hamburger-line:nth-child(1) { top: 18px; }
        .hamburger-line:nth-child(2) { top: 26px; }
        .hamburger-line:nth-child(3) { top: 34px; }

        /* ESTADO ABIERTO → X */
        .open .hamburger-line:nth-child(1) {
            transform: translateY(8px) rotate(45deg);
        }
        .open .hamburger-line:nth-child(2) {
            opacity: 0;
            transform: scale(0);
        }
        .open .hamburger-line:nth-child(3) {
            transform: translateY(-8px) rotate(-45deg);
        }

        .status-badge {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 9999px;
            padding: 0.5rem 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .logo-img {
            height: 100px;
            filter: drop-shadow(0 4px 12px rgba(156, 0, 0, 0.15));
        }

        .sidebar-overlay {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
    </style>
</head>
<body class="font-sans antialiased bg-[var(--bg-light)] text-[var(--text-dark)]">

    <div 
        x-data="{ 
            isMobile: window.innerWidth < 768,
            isSidebarOpen: (window.innerWidth >= 768) ? true : JSON.parse(localStorage.getItem('isSidebarOpen') || 'false'),
            toggleSidebar() {
                this.isSidebarOpen = !this.isSidebarOpen;
                localStorage.setItem('isSidebarOpen', JSON.stringify(this.isSidebarOpen));
                document.documentElement.classList.toggle('sidebar-open', this.isSidebarOpen);
            },
            handleResize() {
                this.isMobile = window.innerWidth < 768;
                if (!this.isMobile) {
                    this.isSidebarOpen = true;
                }
            }
        }" 
        x-init="window.addEventListener('resize', handleResize)"
        class="min-h-screen flex"
    >

        <!-- OVERLAY MÓVIL -->
        <div 
            x-show="isSidebarOpen" 
            @click="toggleSidebar()" 
            class="fixed inset-0 z-40 sidebar-overlay md:hidden"
            x-transition.opacity
            x-cloak
        ></div>

        <!-- SIDEBAR -->
        <aside 
            id="sidebar-nav"
            :class="{'-translate-x-full md:translate-x-0': !isSidebarOpen}"
            class="fixed inset-y-0 left-0 z-50 sidebar-glass transform transition-transform duration-300 ease-in-out overflow-y-auto
                   md:static md:z-auto"
            x-cloak
            aria-label="Navegación principal"
        >
            @include('layouts.navigation')
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col min-h-screen">

            <!-- HEADER CON BOTÓN ANIMADO -->
            <header class="header-glass h-[140px] sticky top-0 z-40">
                <div class="max-w-full mx-auto px-6 h-full flex items-center justify-between">

                    <!-- BOTÓN HAMBURGUESA ANIMADO -->
                    <button 
                        @click="toggleSidebar()" 
                        :class="{'open': isSidebarOpen}"
                        class="btn-hamburger"
                        aria-label="Alternar navegación"
                        :aria-expanded="isSidebarOpen.toString()"
                        aria-controls="sidebar-nav"
                    >
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </button>

                    <!-- LOGO CENTRADO -->
                    <a href="{{ route('dashboard') }}" class="flex-1 flex justify-center">
                        <img src="{{ asset('images/Logo Hidrofrutilla 2.png') }}" 
                             alt="Hidrofrutilla" class="logo-img">
                    </a>

                    <!-- ESTADO -->
                    <div class="status-badge flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-[var(--leaf-green)] animate-pulse"></div>
                        <span class="text-sm font-medium text-[var(--text-muted)]">Sistema Activo</span>
                    </div>
                </div>
            </header>

            <!-- TÍTULO -->
            @if (isset($header))
                <header class="bg-gradient-to-r from-[var(--strawberry-light)] to-white shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-6">
                        <h2 class="text-3xl font-bold page-title">
                            {{ $header }}
                        </h2>
                    </div>
                </header>
            @endif

            <!-- CONTENIDO -->
            <main class="flex-1 p-6 bg-[var(--bg-light)]">
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>

            <!-- FOOTER -->
            <footer class="bg-white/90 backdrop-blur border-t border-[var(--border)] py-5 mt-auto">
                <div class="max-w-7xl mx-auto px-6 text-center">
                    <p class="text-sm text-[var(--text-muted)]">
                        © {{ date('Y') }} <span style="color: var(--strawberry); font-weight: 700;">Hidrofrutilla</span>
                    </p>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>