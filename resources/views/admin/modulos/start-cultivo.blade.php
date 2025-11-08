<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            🌱 Iniciar Cultivo en: <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">{{ $modulo->codigo_identificador }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent mb-8">Detalles del Cultivo</h2>

                <form method="POST" action="{{ route('admin.viveros.modulos.startCultivo', [$vivero, $modulo]) }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="cultivo_actual" class="block font-semibold text-sm text-[#1a1a1a] mb-2">🌱 Nombre del Cultivo (Ej: Fresa Albion)</label>
                            <input id="cultivo_actual" name="cultivo_actual" type="text" value="{{ old('cultivo_actual') }}" required autofocus class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                        </div>
                        <div>
                            <label for="fecha_siembra" class="block font-semibold text-sm text-[#1a1a1a] mb-2">📅 Fecha de Siembra</label>
                            <input id="fecha_siembra" name="fecha_siembra" type="date" value="{{ old('fecha_siembra', now()->toDateString()) }}" required class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-[#e0e0e0]">
                        <a href="{{ route('admin.viveros.modulos.index', $vivero) }}" class="text-[#555555] hover:text-[#9c0000] transition font-medium text-sm">← Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                            ✨ Iniciar Cultivo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>