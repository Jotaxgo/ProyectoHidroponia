<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-hydro-dark">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-hydro-card shadow-xl overflow-hidden sm:rounded-lg">

            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-hydro-accent-gold">HIDROFRUTILLA</h1>
                <h2 class="mt-4 text-2xl font-bold tracking-tight text-hydro-text-light">
                    Establece tu Contraseña
                </h2>
            </div>

            <form method="POST" action="{{ route('invitation.store-password') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block font-medium text-sm text-hydro-text-light">{{ __('Correo Electrónico') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autofocus readonly 
                           class="block mt-1 w-full bg-gray-700/50 border-hydro-border text-gray-400 rounded-md shadow-sm cursor-not-allowed">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-hydro-text-light">{{ __('Nueva Contraseña') }}</label>
                    <input id="password" name="password" type="password" required 
                           class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <label for="password_confirmation" class="block font-medium text-sm text-hydro-text-light">{{ __('Confirmar Contraseña') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                           class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="w-full justify-center inline-flex items-center px-4 py-3 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">
                        {{ __('Guardar Contraseña y Acceder') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>