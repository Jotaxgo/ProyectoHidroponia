<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            ✏️ Editar Módulo en: <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">{{ $vivero->nombre }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent mb-8">Editando: 🔧 {{ $modulo->codigo_identificador }}</h2>

                @if ($errors->any())
                    <div class="bg-red-500/20 border border-red-500/40 text-red-700 p-4 rounded-lg mb-6">
                        <strong class="font-bold">⚠️ Error de Validación!</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.viveros.modulos.update', [$vivero, $modulo]) }}">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <label for="codigo_identificador" class="block font-semibold text-sm text-[#1a1a1a] mb-2">🏷️ Código Identificador (Ej: A-01)</label>
                            <input id="codigo_identificador" name="codigo_identificador" type="text" value="{{ old('codigo_identificador', $modulo->codigo_identificador) }}" required autofocus class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                        </div>

                        <div>
                            <label for="capacidad" class="block font-semibold text-sm text-[#1a1a1a] mb-2">🌱 Capacidad (Nº de plantas)</label>
                            <input id="capacidad" name="capacidad" type="number" value="{{ old('capacidad', $modulo->capacidad) }}" required class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                        </div>
                        
                        <div>
                            <label for="estado" class="block font-semibold text-sm text-[#1a1a1a] mb-2">⚙️ Estado</label>
                            <select name="estado" id="estado" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                                <option value="Disponible" {{ old('estado', $modulo->estado) == 'Disponible' ? 'selected' : '' }}>✅ Disponible</option>
                                <option value="Ocupado" {{ old('estado', $modulo->estado) == 'Ocupado' ? 'selected' : '' }}>🌱 Ocupado</option>
                                <option value="Mantenimiento" {{ old('estado', $modulo->estado) == 'Mantenimiento' ? 'selected' : '' }}>🔧 Mantenimiento</option>
                            </select>
                        </div>

                        <div>
                            <label for="device_id" class="block font-semibold text-sm text-[#1a1a1a] mb-2">📡 ID del Dispositivo (Hardware)</label>
                            <input id="device_id" name="device_id" type="text" value="{{ old('device_id', $modulo->hardware_info['device_id'] ?? '') }}" placeholder="Ej: MOD-A01-HW-12345" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                        </div>

                        <div class="pt-6 border-t border-[#e0e0e0] space-y-4">
                            <h3 class="text-lg font-bold text-[#1a1a1a]">⚙️ Límites para Alertas (Opcional)</h3>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="ph_min" class="block font-semibold text-sm text-[#1a1a1a] mb-2">pH Mínimo</label>
                                    <input id="ph_min" name="ph_min" type="number" step="0.1" value="{{ old('ph_min', $modulo->hardware_info['ph_min'] ?? '') }}" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                                </div>
                                <div>
                                    <label for="ph_max" class="block font-semibold text-sm text-[#1a1a1a] mb-2">pH Máximo</label>
                                    <input id="ph_max" name="ph_max" type="number" step="0.1" value="{{ old('ph_max', $modulo->hardware_info['ph_max'] ?? '') }}" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                                </div>
                                <div>
                                    <label for="temp_min" class="block font-semibold text-sm text-[#1a1a1a] mb-2">Temp. Mínima (°C)</label>
                                    <input id="temp_min" name="temp_min" type="number" step="0.1" value="{{ old('temp_min', $modulo->hardware_info['temp_min'] ?? '') }}" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                                </div>
                                <div>
                                    <label for="temp_max" class="block font-semibold text-sm text-[#1a1a1a] mb-2">Temp. Máxima (°C)</label>
                                    <input id="temp_max" name="temp_max" type="number" step="0.1" value="{{ old('temp_max', $modulo->hardware_info['temp_max'] ?? '') }}" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-[#e0e0e0]">
                        <a href="{{ url()->previous() }}" class="text-[#555555] hover:text-[#9c0000] transition font-medium text-sm">← Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                            💾 Actualizar Módulo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>