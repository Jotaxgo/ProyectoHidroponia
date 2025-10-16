<x-guest-layout>
    <div class="min-h-screen flex">
        <div class="hidden lg:block w-1/2 bg-cover bg-center relative" style="background-image: url('{{ asset('images/hidroponia-login.jpg') }}');">
            <div class="absolute inset-0 bg-hydro-dark/70"></div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-hydro-card">
            <div class="w-full max-w-md space-y-8">
                <div class="text-center">
                    
                    <h1 class="text-3xl font-bold text-hydro-accent-gold">HIDROFRUTILLA</h1>
                    <h2 class="mt-4 text-2xl font-bold tracking-tight text-hydro-text-light">
                        Inicia sesión en tu cuenta
                    </h2>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                    
                    @csrf

                    <div>
                        <label for="email" class="block font-medium text-sm text-hydro-text-light">Correo Electrónico</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <label for="password" class="block font-medium text-sm text-hydro-text-light">Contraseña</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded bg-hydro-dark border-hydro-border text-hydro-accent-green shadow-sm focus:ring-hydro-accent-green" name="remember">
                            <span class="ms-2 text-sm text-gray-400">{{ __('Recordarme') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-400 hover:text-white rounded-md" href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="w-full justify-center inline-flex items-center px-4 py-3 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">
                            Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
<!-- <x-guest-layout>
    <div class="min-h-screen flex">
        {{-- Columna de la Imagen --}}
        <div class="hidden lg:block w-1/2 bg-cover bg-center relative" style="background-image: url('{{ asset('images/hidroponia-login.jpg') }}');">
            <div class="absolute inset-0 bg-hydro-dark/70"></div>
        </div>

        {{-- Columna del Formulario --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-hydro-card"> {{-- Aplicar hydro-card aquí --}}
            <div class="w-full max-w-md space-y-8">
                <div class="text-center">
                    
                    <h1 class="text-3xl font-bold text-hydro-accent-gold text-center">HIDROFRUTILLA</h1>
                    <h2 class="mt-4 text-2xl font-bold tracking-tight text-hydro-text-light">
                        Inicia sesión en tu cuenta
                    </h2>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block font-medium text-sm text-hydro-text-light">Correo Electrónico</label>
                        <input id="email" name="email" type="email" :value="old('email')" required autofocus class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <label for="password" class="block font-medium text-sm text-hydro-text-light">Contraseña</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded bg-hydro-dark border-hydro-border text-hydro-accent-green shadow-sm focus:ring-hydro-accent-green" name="remember">
                            <span class="ms-2 text-sm text-gray-400">{{ __('Recordarme') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-400 hover:text-white rounded-md" href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="w-full justify-center inline-flex items-center px-4 py-3 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">
                            Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout> -->
