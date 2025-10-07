<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Añadir Módulo a: <span class="text-hydro-accent-gold">{{ $vivero->nombre }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold text-white mb-6">Detalles del Nuevo Módulo</h2>

                @if ($errors->any())
                    <div class="bg-red-500/20 text-red-300 p-4 rounded mb-6">
                        <strong class="font-bold">¡Error de Validación!</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.viveros.modulos.store', $vivero) }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="codigo_identificador" class="block font-medium text-sm text-hydro-text-light">Código Identificador (Ej: A-01)</label>
                            <input id="codigo_identificador" name="codigo_identificador" type="text" value="{{ old('codigo_identificador') }}" required autofocus class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        </div>

                        <div>
                            <label for="capacidad" class="block font-medium text-sm text-hydro-text-light">Capacidad (Nº de plantas)</label>
                            <input id="capacidad" name="capacidad" type="number" value="{{ old('capacidad') }}" required class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        </div>

                        <div>
                            <label for="device_id" class="block font-medium text-sm text-hydro-text-light">ID del Dispositivo (Hardware)</label>
                            <input id="device_id" name="device_id" type="text" value="{{ old('device_id') }}" placeholder="Ej: MOD-A01-HW-12345" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        </div>

                        <div class="pt-6 border-t border-hydro-border space-y-4">
                            <h3 class="text-lg font-medium text-white">Límites para Alertas (Opcional)</h3>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="ph_min" class="block font-medium text-sm text-hydro-text-light">pH Mínimo</label>
                                    <input id="ph_min" name="ph_min" type="number" step="0.1" value="{{ old('ph_min') }}" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="ph_max" class="block font-medium text-sm text-hydro-text-light">pH Máximo</label>
                                    <input id="ph_max" name="ph_max" type="number" step="0.1" value="{{ old('ph_max') }}" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="temp_min" class="block font-medium text-sm text-hydro-text-light">Temp. Mínima (°C)</label>
                                    <input id="temp_min" name="temp_min" type="number" step="0.1" value="{{ old('temp_min') }}" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="temp_max" class="block font-medium text-sm text-hydro-text-light">Temp. Máxima (°C)</label>
                                    <input id="temp_max" name="temp_max" type="number" step="0.1" value="{{ old('temp_max') }}" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white rounded-md shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ url()->previous() }}" class="text-gray-400 hover:text-white mr-6">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">
                            Añadir Módulo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>