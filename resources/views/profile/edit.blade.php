<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-hydro-card shadow-xl sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-hydro-card shadow-xl sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-hydro-card shadow-xl sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-8 bg-hydro-card shadow-xl sm:rounded-lg">
            <div class="max-w-xl">
                <header>
                    <h2 class="text-lg font-medium text-hydro-text-light">
                        Gestión de API Keys
                    </h2>
                    <p class="mt-1 text-sm text-gray-400">
                        Genera y gestiona las API Keys para conectar tus dispositivos de hardware.
                    </p>
                </header>

                <form method="POST" action="{{ route('profile.tokens.create') }}" class="mt-6 space-y-6">
                    @csrf
                    <div>
                        <label for="token_name" class="block font-medium text-sm text-hydro-text-light">Nombre de la Key</label>
                        <input id="token_name" name="token_name" type="text" required class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white rounded-md shadow-sm" placeholder="Ej: ESP32 del Vivero Central">
                    </div>
                    <div class="flex items-center gap-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">Generar Key</button>
                    </div>
                </form>

                @if (session('newToken'))
                    <div class="mt-6 p-4 bg-yellow-500/20 text-yellow-300 rounded-md">
                        <p class="font-bold">¡API Key Generada! Cópiala ahora, no podrás verla de nuevo:</p>

                        <div x-data="{
                                token: '{{ session('newToken') }}',
                                copied: false,
                                copyToClipboard() {
                                    navigator.clipboard.writeText(this.token);
                                    this.copied = true;
                                    setTimeout(() => { this.copied = false }, 2000);
                                }
                            }" class="relative mt-2">

                            <input type="text" readonly :value="token" class="w-full bg-hydro-dark border-hydro-border rounded-md text-white p-2 pr-10">

                            <button @click="copyToClipboard()" type="button" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-white">
                                <svg x-show="!copied" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <svg x-show="copied" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                        </div>
                @endif

                <div class="mt-6 space-y-4">
                    <h3 class="text-md font-medium text-hydro-text-light">Keys Activas</h3>
                    @forelse (Auth::user()->tokens as $token)
                        <div class="flex items-center justify-between p-3 bg-hydro-dark rounded-md">
                            <p class="text-white">{{ $token->name }}</p>
                            <form method="POST" action="{{ route('profile.tokens.destroy', $token->id) }}" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta key? Cualquier dispositivo que la use dejará de funcionar.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-400 text-sm">Eliminar</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">No tienes ninguna API Key activa.</p>
                    @endforelse
                </div>
            </div>
        </div>
    
</x-app-layout>