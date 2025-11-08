<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            📊 <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Generar Reporte de Módulo</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" x-data="{ reportType: '{{ old('report_type', 'custom') }}' }" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent mb-8">Selecciona los Parámetros</h2>

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
                <form method="GET" action="{{ route('admin.reportes.module.show') }}">
                    <div class="space-y-6">
                        
                        <div class="space-y-3">
                            <label class="block font-semibold text-sm text-[#1a1a1a]">🎯 Tipo de Reporte</label>
                            <div class="flex items-center space-x-6">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" x-model="reportType" value="custom" name="report_type" class="w-4 h-4">
                                    <span class="ml-2 text-[#555555] font-medium">📅 Rango Personalizado</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" x-model="reportType" value="cultivo" name="report_type" class="w-4 h-4">
                                    <span class="ml-2 text-[#555555] font-medium">🌱 Por Cultivo Activo</span>
                                </label>
                            </div>
                        </div>

                        <div x-show="reportType === 'cultivo'" class="p-4 bg-[#fafafa] rounded-lg border border-[#e0e0e0]">
                            <label for="modulo_id_cultivo" class="block font-semibold text-sm text-[#1a1a1a] mb-2">🌱 Selecciona un Cultivo Activo</label>
                            <select name="modulo_id" id="modulo_id_cultivo" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                                <option value="" disabled selected>-- Elige un cultivo --</option>
                                @foreach($modulosOcupados as $modulo)
                                    <option value="{{ $modulo->id }}">{{ $modulo->vivero->nombre }} / {{ $modulo->codigo_identificador }} ({{ $modulo->cultivo_actual }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div x-show="reportType === 'custom'" class="space-y-6 p-4 bg-[#fafafa] rounded-lg border border-[#e0e0e0]">
                            <div>
                                <label for="modulo_id_custom" class="block font-semibold text-sm text-[#1a1a1a] mb-2">🔧 Selecciona un Módulo</label>
                                <select name="modulo_id_custom" id="modulo_id_custom" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                                    @foreach($modulos as $modulo)
                                        <option value="{{ $modulo->id }}">{{ $modulo->vivero->nombre }} - {{ $modulo->codigo_identificador }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <button type="button" @click="setDates(7)" class="text-xs bg-[#96d900] hover:bg-[#7eb800] text-white py-2 px-3 rounded-lg font-medium transition">📆 Últimos 7 Días</button>
                                <button type="button" @click="setDates(30)" class="text-xs bg-[#96d900] hover:bg-[#7eb800] text-white py-2 px-3 rounded-lg font-medium transition">📆 Últimos 30 Días</button>
                                <button type="button" @click="setThisMonth()" class="text-xs bg-[#96d900] hover:bg-[#7eb800] text-white py-2 px-3 rounded-lg font-medium transition">📅 Este Mes</button>
                            </div>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="fecha_inicio" class="block font-semibold text-sm text-[#1a1a1a] mb-2">📅 Fecha de Inicio</label>
                                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                                </div>
                                <div>
                                    <label for="fecha_fin" class="block font-semibold text-sm text-[#1a1a1a] mb-2">📅 Fecha de Fin</label>
                                    <input type="date" id="fecha_fin" name="fecha_fin" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-[#e0e0e0]">
                        <a href="{{ route('dashboard') }}" class="text-[#555555] hover:text-[#9c0000] transition font-medium text-sm">← Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                            📊 Ver Reporte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function setDates(days) {
        const endDate = new Date();
        const startDate = new Date();
        startDate.setDate(endDate.getDate() - (days - 1));
        
        document.getElementById('fecha_fin').value = endDate.toISOString().split('T')[0];
        document.getElementById('fecha_inicio').value = startDate.toISOString().split('T')[0];
    }

    function setThisMonth() {
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        
        document.getElementById('fecha_fin').value = today.toISOString().split('T')[0];
        document.getElementById('fecha_inicio').value = firstDay.toISOString().split('T')[0];
    }
</script>