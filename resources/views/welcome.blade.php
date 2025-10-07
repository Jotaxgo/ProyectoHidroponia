<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-g">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Hidrofrutilla</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-hydro-dark text-hydro-text-light">
        <div class="relative min-h-screen flex flex-col items-center justify-center">
            
            @if (Route::has('login'))
                <div class="absolute top-0 right-0 p-6 text-right">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-400 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-400 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Iniciar Sesión</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-400 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Registrarse</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex justify-center">
                    <h1 class="text-5xl md:text-7xl font-bold text-hydro-accent-gold">HIDROFRUTILLA</h1>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-lg text-gray-400">
                        Gestiona y monitorea tus cultivos hidropónicos de forma inteligente.
                    </p>
                </div>

                <div class="mt-10 flex justify-center">
                    <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-hydro-accent-bright hover:bg-hydro-accent-light text-hydro-dark font-bold rounded-md transition-colors duration-300">
                        Acceder al Sistema
                    </a>
                </div>
            </div>

            <footer class="absolute bottom-0 py-4">
                <p class="text-center text-sm text-gray-500">
                    © {{ date('Y') }} Hidrofrutilla. Todos los derechos reservados.
                </p>
            </footer>
        </div>
    </body>
</html>