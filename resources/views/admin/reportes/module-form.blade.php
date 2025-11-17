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

                        <div x-show="reportType === 'custom'" class="space-y-6 p-4 bg-[#fafafa] rounded-lg border border-[#e0e0e0]" id="custom-report-form">
                            @if(Auth::user()->role->nombre_rol === 'Admin')
                            <div>
                                <label for="user_id" class="block font-semibold text-sm text-[#1a1a1a] mb-2">1. Selecciona un Usuario</label>
                                <select id="user_id" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                                    <option value="">-- Elige un usuario --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->full_name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div>
                                <label for="vivero_id" class="block font-semibold text-sm text-[#1a1a1a] mb-2">
                                    {{ Auth::user()->role->nombre_rol === 'Admin' ? '2. Selecciona un Vivero' : '1. Selecciona un Vivero' }}
                                </label>
                                <select id="vivero_id" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition" disabled>
                                    <option value="">-- {{ Auth::user()->role->nombre_rol === 'Admin' ? 'Primero elige un usuario' : 'Cargando...' }} --</option>
                                </select>
                            </div>

                            <div>
                                <label for="modulo_id_custom" class="block font-semibold text-sm text-[#1a1a1a] mb-2">
                                     {{ Auth::user()->role->nombre_rol === 'Admin' ? '3. Selecciona un Módulo' : '2. Selecciona un Módulo' }}
                                </label>
                                <select name="modulo_id_custom" id="modulo_id_custom" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition" disabled>
                                    <option value="">-- Primero elige un vivero --</option>
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

    document.addEventListener('DOMContentLoaded', function() {
        const userSelect = document.getElementById('user_id');
        const viveroSelect = document.getElementById('vivero_id');
        const moduloSelect = document.getElementById('modulo_id_custom');
        
        const isAdmin = {{ Auth::user()->role->nombre_rol === 'Admin' ? 'true' : 'false' }};
        const currentUserId = {{ Auth::id() }};

        function resetViveroSelect() {
            viveroSelect.innerHTML = `<option value="">-- ${isAdmin ? 'Primero elige un usuario' : 'Selecciona un vivero'} --</option>`;
            viveroSelect.disabled = true;
            resetModuloSelect();
        }

        function resetModuloSelect() {
            moduloSelect.innerHTML = '<option value="">-- Primero elige un vivero --</option>';
            moduloSelect.disabled = true;
        }

        function fetchViveros(userId) {
            if (!userId) {
                resetViveroSelect();
                return;
            }
            
            viveroSelect.innerHTML = '<option value="">Cargando viveros...</option>';
            viveroSelect.disabled = false;

            fetch(`/admin/api/users/${userId}/viveros`, { 
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
            })
                .then(response => {
                    if (!response.ok) throw new Error('Respuesta de red no fue OK');
                    return response.json();
                })
                .then(data => {
                    viveroSelect.innerHTML = '<option value="">-- Selecciona un vivero --</option>';
                    if (data.length === 0) {
                        viveroSelect.innerHTML = '<option value="">-- Este usuario no tiene viveros --</option>';
                        viveroSelect.disabled = true;
                    } else {
                        data.forEach(vivero => {
                            const option = new Option(vivero.nombre, vivero.id);
                            viveroSelect.add(option);
                        });
                        viveroSelect.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error fetching viveros:', error);
                    viveroSelect.innerHTML = '<option value="">-- Error al cargar viveros --</option>';
                });
        }

        if (isAdmin) {
            // Inicializar Select2 para el campo de usuario
            $(userSelect).select2({
                placeholder: "-- Busca un usuario --",
                allowClear: true,
                width: 'resolve' // Ajusta el ancho al contenedor
            });

            $(userSelect).on('change', function () {
                const userId = $(this).val();
                fetchViveros(userId);
                resetModuloSelect();
            });
        } else {
            fetchViveros(currentUserId);
        }

        viveroSelect.addEventListener('change', () => {
            const viveroId = viveroSelect.value;
            if (!viveroId) {
                resetModuloSelect();
                return;
            }

            moduloSelect.innerHTML = '<option value="">Cargando módulos...</option>';
            moduloSelect.disabled = false;

            fetch(`/admin/api/viveros/${viveroId}/modulos`, { 
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
            })
                .then(response => {
                    if (!response.ok) throw new Error('Respuesta de red no fue OK');
                    return response.json();
                })
                .then(data => {
                    moduloSelect.innerHTML = '<option value="">-- Selecciona un módulo --</option>';
                    if (data.length === 0) {
                        moduloSelect.innerHTML = '<option value="">-- Este vivero no tiene módulos --</option>';
                        moduloSelect.disabled = true;
                    } else {
                        data.forEach(modulo => {
                            const option = new Option(modulo.codigo_identificador, modulo.id);
                            moduloSelect.add(option);
                        });
                        moduloSelect.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error fetching modulos:', error);
                    moduloSelect.innerHTML = '<option value="">-- Error al cargar módulos --</option>';
                });
        });
    });
</script>
</x-app-layout>