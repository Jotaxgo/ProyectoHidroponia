<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Hidrofrutilla: Monitoreo inteligente de cultivos hidropónicos de frutilla con tecnología de punta.">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Hidrofrutilla - Cultivo Hidropónico de Frutilla</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|figtree:700&display=swap" rel="stylesheet" />

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- PALETA DE FRUTILLA REAL + ESTILOS ESTÉTICOS -->
    <style>
        :root {
            --strawberry-dark: #9c0000;
            --strawberry: #ff4b65;
            --strawberry-light: #ffdef;
            --leaf-green: #96d900;
            --leaf-dark: #058c00;
            --bg-light: #ffffff;
            --text-dark: #1a1a1a;
            --text-muted: #555555;
            --border: #e0e0e0;
        }

        body { background: var(--bg-light); color: var(--text-dark); }

        /* Título con gradiente natural de frutilla */
        .title-gradient {
            font-weight: 800;
            background: linear-gradient(135deg, var(--strawberry-dark), var(--strawberry), var(--leaf-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 4px 20px rgba(255, 75, 101, 0.15);
        }

        /* Botón CTA con brillo frutilla */
        .btn-cta {
            background: linear-gradient(135deg, var(--strawberry-dark), var(--strawberry));
            color: white;
            font-weight: 700;
            padding: 1rem 3rem;
            border-radius: 9999px;
            box-shadow: 
                0 10px 25px rgba(255, 75, 101, 0.3),
                0 0 0 4px rgba(255, 75, 101, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }
        .btn-cta::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.7s;
        }
        .btn-cta:hover::before { left: 100%; }
        .btn-cta:hover {
            transform: translateY(-6px) scale(1.05);
            box-shadow: 
                0 20px 40px rgba(255, 75, 101, 0.4),
                0 0 0 6px rgba(255, 75, 101, 0.15);
        }

        /* Tarjetas suaves con vidrio esmerilado */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.1);
        }

        /* Logo SVG con frutilla realista */
        .logo-frutilla {
            filter: drop-shadow(0 10px 20px rgba(156, 0, 0, 0.15));
        }
    </style>
</head>
<body class="font-sans antialiased min-h-screen flex flex-col">



    <!-- Hero Principal -->
    <main class="flex-1 flex items-center justify-center px-6 py-16">
        <div class="max-w-5xl mx-auto text-center space-y-12">

            <!-- Logo + Título -->
            <div class="space-y-8">

                <h1 class="text-7xl md:text-9xl font-bold title-gradient leading-none">
                    HIDROFRUTILLA
                </h1>
            </div>

            <!-- Subtítulo natural -->
            <p class="text-xl md:text-2xl text-[var(--text-muted)] max-w-3xl mx-auto leading-relaxed font-medium">
                Cultiva frutillas <span style="color: var(--leaf-green); font-weight: 700;">perfectas</span> con 
                <span style="color: var(--strawberry); font-weight: 700;">hidroponía</span> de 
                <span style="color: var(--leaf-dark); font-weight: 700;">alta precisión</span>.
            </p>

            <!-- CTA -->
            @guest
                <div class="mt-16">
                    <a href="{{ route('login') }}" class="btn-cta inline-flex items-center space-x-3 text-lg">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" 
                                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>Acceder al Sistema</span>
                    </a>
                </div>
            @endguest

            <!-- Tarjetas de valor -->
            <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="glass-card p-8 text-center">
                    <div class="text-5xl mb-3" style="color: var(--leaf-green);">pH</div>
                    <p class="text-[var(--text-muted)] font-medium">5.5 – 6.5</p>
                    <p class="text-sm text-[var(--text-muted)] mt-1">Control óptimo</p>
                </div>
                <div class="glass-card p-8 text-center">
                    <div class="text-5xl mb-3" style="color: var(--strawberry);">EC</div>
                    <p class="text-[var(--text-muted)] font-medium">1.2 – 2.0 mS/cm</p>
                    <p class="text-sm text-[var(--text-muted)] mt-1">Nutrientes ideales</p>
                </div>
                <div class="glass-card p-8 text-center">
                    <div class="text-5xl mb-3" style="color: var(--leaf-dark);">Temp</div>
                    <p class="text-[var(--text-muted)] font-medium">18 – 24°C</p>
                    <p class="text-sm text-[var(--text-muted)] mt-1">Crecimiento perfecto</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-8 border-t border-[var(--border)] bg-white/70 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-sm text-[var(--text-muted)]">
                © {{ date('Y') }} <span style="color: var(--strawberry); font-weight: 700;">Hidrofrutilla</span>. 
                Todos los derechos reservados.
            </p>
        </div>
    </footer>
</body>
</html>