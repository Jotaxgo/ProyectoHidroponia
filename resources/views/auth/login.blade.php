<x-guest-layout>
    <div class="min-h-screen flex">

        <!-- IMAGEN DE FONDO (solo lg+) -->
        <div class="hidden lg:block w-1/2 bg-cover bg-center relative" 
             style="background-image: url('{{ asset('images/hidroponia-login.jpg') }}');">
            <div class="absolute inset-0 bg-gradient-to-br from-[#9c0000]/80 via-[#ff4b65]/60 to-[#058c00]/70"></div>
            <div class="absolute bottom-10 left-10 text-white">
                <h3 class="text-4xl font-bold drop-shadow-2xl tracking-tight">HIDROFRUTILLA</h3>
                <p class="text-lg mt-2 opacity-90 drop-shadow-lg font-medium">Cultivo inteligente de frutilla</p>
            </div>
        </div>

        <!-- FORMULARIO -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gradient-to-br from-[#ffffff] to-[#ffdef]/30">
            <div class="w-full max-w-md space-y-8">

                <!-- TÍTULO -->
                <div class="text-center">
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent drop-shadow-sm">
                        HIDROFRUTILLA
                    </h1>
                    <h2 class="mt-4 text-xl font-medium text-[#555555]">
                        Inicia sesión en tu cuenta
                    </h2>
                </div>

                <!-- MENSAJE DE ESTADO -->
                <x-auth-session-status class="mb-6 text-center text-sm text-[#555555]" :status="session('status')" />

                <!-- FORMULARIO -->
                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                    @csrf

                    <!-- EMAIL -->
                    <div>
                        <label for="email" class="block font-semibold text-sm text-[#1a1a1a] mb-2">
                            Correo Electrónico
                        </label>
                        <div class="relative">
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                class="block mt-1 w-full px-4 py-3 bg-white border border-[#e0e0e0] text-[#1a1a1a] rounded-xl shadow-sm focus:ring-2 focus:ring-[#ff4b65] focus:border-[#ff4b65] transition-all duration-200 placeholder-[#888]"
                                placeholder="tu@email.com"
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-[#888]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                    </div>

                    <!-- CONTRASEÑA -->
                    <div class="mt-6">
                        <label for="password" class="block font-semibold text-sm text-[#1a1a1a] mb-2">
                            Contraseña
                        </label>
                        <div class="relative">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required 
                                autocomplete="current-password"
                                class="block mt-1 w-full px-4 py-3 bg-white border border-[#e0e0e0] text-[#1a1a1a] rounded-xl shadow-sm focus:ring-2 focus:ring-[#ff4b65] focus:border-[#ff4b65] transition-all duration-200 placeholder-[#888]"
                                placeholder="••••••••"
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-[#888]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                    </div>

                    <!-- RECORDARME + OLVIDÉ -->
                    <div class="flex items-center justify-between mt-6 text-sm">
                        <label class="inline-flex items-center cursor-pointer">
                            <input 
                                id="remember_me" 
                                type="checkbox" 
                                class="w-5 h-5 rounded border-[#e0e0e0] text-[#ff4b65] focus:ring-[#ff4b65] shadow-sm"
                                name="remember"
                            >
                            <span class="ms-2 text-[#555555] font-medium">{{ __('Recordarme') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-[#ff4b65] hover:text-[#9c0000] font-medium transition underline" 
                               href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                        @endif
                    </div>

                    <!-- BOTÓN INICIAR SESIÓN -->
                    <div class="flex items-center justify-end mt-8">
                        <button type="submit" 
                                class="w-full flex justify-center items-center px-6 py-4 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white font-bold text-base rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-[#ff4b65]/30">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Iniciar Sesión
                        </button>
                    </div>
                </form>

                <!-- ENLACE REGISTRO ELIMINADO (OPCIÓN 1) -->
            </div>
        </div>
    </div>

    <!-- ESTILOS LOCALES (para mantener hydro-* y mejorar visualmente) -->
    <style>
        .bg-hydro-card { 
            background: linear-gradient(135deg, #ffffff, #ffdef30) !important; 
        }
        .text-hydro-text-light { 
            color: #1a1a1a !important; 
        }
        .bg-hydro-dark { 
            background-color: #ffffff !important; 
        }
        .border-hydro-border { 
            border-color: #e0e0e0 !important; 
        }
        .text-hydro-accent-gold { 
            background: linear-gradient(to right, #9c0000, #ff4b65);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }
        .focus\:ring-hydro-accent-gold:focus { 
            --tw-ring-color: #ff4b65; 
        }
        .focus\:border-hydro-accent-gold:focus { 
            border-color: #ff4b65; 
        }
        .text-hydro-accent-green { 
            color: #96d900; 
        }
        .focus\:ring-hydro-accent-green:focus { 
            --tw-ring-color: #96d900; 
        }
    </style>
</x-guest-layout>