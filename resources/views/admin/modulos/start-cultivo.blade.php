<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Iniciar Cultivo en: <span class="text-hydro-accent-gold">{{ $modulo->codigo_identificador }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold text-white mb-6">Detalles del Cultivo</h2>

                <form method="POST" action="{{ route('admin.viveros.modulos.startCultivo', [$vivero, $modulo]) }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="cultivo_actual" class="block font-medium text-sm text-hydro-text-light">Nombre del Cultivo (Ej: Fresa Albion)</label>
                            <input id="cultivo_actual" name="cultivo_actual" type="text" value="{{ old('cultivo_actual') }}" required autofocus class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="fecha_siembra" class="block font-medium text-sm text-hydro-text-light">Fecha de Siembra</label>
                            <input id="fecha_siembra" name="fecha_siembra" type="date" value="{{ old('fecha_siembra', now()->toDateString()) }}" required class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ route('admin.viveros.modulos.index', $vivero) }}" class="text-gray-400 hover:text-white mr-6">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">
                            Iniciar Cultivo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>