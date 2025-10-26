<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">Generar Reporte de Módulo</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6" x-data="{ reportType: '{{ old('report_type', 'custom') }}' }">
                <h2 class="text-2xl font-bold text-white mb-6">Selecciona los Parámetros</h2>

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
                <form method="GET" action="{{ route('admin.reportes.module.show') }}">
                    <div class="space-y-6">
                        
                        <div class="space-y-2">
                            <label class="block font-medium text-sm text-hydro-text-light">Tipo de Reporte</label>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center"><input type="radio" x-model="reportType" value="custom" name="report_type" class="form-radio bg-hydro-dark border-hydro-border text-hydro-accent-gold"><span class="ml-2 text-hydro-text-light">Rango Personalizado</span></label>
                                <label class="flex items-center"><input type="radio" x-model="reportType" value="cultivo" name="report_type" class="form-radio bg-hydro-dark border-hydro-border text-hydro-accent-gold"><span class="ml-2 text-hydro-text-light">Por Cultivo Activo</span></label>
                            </div>
                        </div>

                        <div x-show="reportType === 'cultivo'">
                            <label for="modulo_id_cultivo" class="block font-medium text-sm text-hydro-text-light">Selecciona un Cultivo Activo</label>
                            <select name="modulo_id" id="modulo_id_cultivo" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white rounded-md shadow-sm">
                                <option value="" disabled selected>-- Elige un cultivo --</option>
                                @foreach($modulosOcupados as $modulo)
                                    <option value="{{ $modulo->id }}">{{ $modulo->vivero->nombre }} / {{ $modulo->codigo_identificador }} ({{ $modulo->cultivo_actual }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div x-show="reportType === 'custom'">
                            <div class="space-y-6">
                                <div>
                                    <label for="modulo_id_custom" class="block font-medium text-sm text-hydro-text-light">Selecciona un Módulo</label>
                                    <select name="modulo_id_custom" id="modulo_id_custom" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white rounded-md shadow-sm">
                                        @foreach($modulos as $modulo)
                                            <option value="{{ $modulo->id }}">{{ $modulo->vivero->nombre }} - {{ $modulo->codigo_identificador }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <button type="button" @click="setDates(7)" class="text-xs bg-gray-600 hover:bg-gray-500 text-white py-1 px-3 rounded-full">Últimos 7 Días</button>
                                    <button type="button" @click="setDates(30)" class="text-xs bg-gray-600 hover:bg-gray-500 text-white py-1 px-3 rounded-full">Últimos 30 Días</button>
                                    <button type="button" @click="setThisMonth()" class="text-xs bg-gray-600 hover:bg-gray-500 text-white py-1 px-3 rounded-full">Este Mes</button>
                                </div>
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <label for="fecha_inicio" class="block font-medium text-sm text-hydro-text-light">Fecha de Inicio</label>
                                        <input type="date" id="fecha_inicio" name="fecha_inicio" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white rounded-md shadow-sm">
                                    </div>
                                    <div>
                                        <label for="fecha_fin" class="block font-medium text-sm text-hydro-text-light">Fecha de Fin</label>
                                        <input type="date" id="fecha_fin" name="fecha_fin" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white rounded-md shadow-sm">
                                    </div>
                                </div>
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