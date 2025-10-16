<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Generar Reporte de Módulo
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold text-white mb-6">Selecciona los Parámetros</h2>

                @if ($errors->any())
                    <div class="bg-red-500/20 text-red-300 p-4 rounded mb-6">
                        <strong class="font-bold">¡Error!</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="GET" action="{{ route('admin.reportes.module.show') }}">
                    <div class="space-y-6">
                        <div>
                            <label for="modulo_id" class="block font-medium text-sm text-hydro-text-light">Selecciona un Módulo</label>
                            <select name="modulo_id" id="modulo_id" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm" required>
                                <option value="" disabled selected>-- Elige un módulo --</option>
                                @foreach($modulos as $modulo)
                                    <option value="{{ $modulo->id }}">{{ $modulo->vivero->nombre }} - {{ $modulo->codigo_identificador }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="fecha_inicio" class="block font-medium text-sm text-hydro-text-light">Fecha de Inicio</label>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" required class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="fecha_fin" class="block font-medium text-sm text-hydro-text-light">Fecha de Fin</label>
                                <input type="date" id="fecha_fin" name="fecha_fin" required class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white rounded-md shadow-sm">
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-8">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">
                            Ver Reporte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>