<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Gestionar Módulos del Vivero: <span class="text-hydro-accent-gold">{{ $vivero->nombre }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ====================================================== --}}
            {{-- SECCIÓN 1: TABLA DE MONITOREO EN TIEMPO REAL --}}
            {{-- ====================================================== --}}
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent mb-6">
                    📊 Monitoreo en Tiempo Real
                </h2>
                
                {{-- Contenedor donde se dibujará la tabla de monitoreo --}}
                <div id="monitoreo-table-container">
                    <p class="text-[#999999] animate-pulse">Cargando datos de módulos...</p>
                </div>
            </div>


            {{-- ====================================================== --}}
            {{-- SECCIÓN 2: GESTIÓN DE MÓDULOS (TU CÓDIGO EXISTENTE) --}}
            {{-- ====================================================== --}}
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">

                <div class="flex justify-between items-center mb-8">
                    <div class="flex flex-col gap-2">
                        @if(Auth::user()->role->nombre_rol == 'Admin')
                            <a href="{{ route('admin.viveros.index') }}" class="text-[#555555] hover:text-[#9c0000] transition font-medium text-sm inline-block">
                                ← Volver a Viveros
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-[#555555] hover:text-[#9c0000] transition font-medium text-sm inline-block">
                                ← Volver a Dashboard
                            </a>
                        @endif
                    </div>
                    <div class="flex gap-4">
                        <a href="{{ route('admin.viveros.modulos.trash', $vivero) }}" class="text-[#555555] hover:text-[#9c0000] transition font-medium text-sm">🗑️ Papelera</a>
                        <a href="{{ route('admin.viveros.modulos.create', $vivero) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white rounded-lg text-xs font-semibold hover:shadow-lg transition">
                            + Nuevo Módulo
                        </a>
                    </div>
                </div>

                <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent mb-6">Gestión de Módulos</h2>

                {{-- Tu tabla de gestión CRUD existente --}}
                <div class="relative overflow-x-auto rounded-xl border border-[#e0e0e0]">
                    <table class="w-full text-sm">
                        <thead class="bg-gradient-to-r from-[#fafafa] to-[#f5f5f5] border-b border-[#e0e0e0]">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Código</th>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Device ID</th>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Estado</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e0e0e0]">
                            @forelse ($modulos as $modulo)
                            <tr class="hover:bg-[#ffdef0]/30 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg">🔧</span>
                                        <span class="font-semibold text-[#1a1a1a]">{{ $modulo->codigo_identificador }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[#555555]">{{ $modulo->hardware_info['device_id'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        @if($modulo->estado == 'Disponible') bg-[#96d900]/20 text-[#6b9b00]
                                        @elseif($modulo->estado == 'Ocupado') bg-amber-400/20 text-amber-700
                                        @else bg-red-500/20 text-red-600 @endif">
                                        @if($modulo->estado == 'Disponible')✅ @elseif($modulo->estado == 'Ocupado')🌱 @else🔧 @endif {{ $modulo->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2 flex-wrap">
                                        {{-- Botón para controlar la bomba --}}
                                        <button
                                            class="bomba-toggle-btn inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold transition
                                                {{ $modulo->bomba_estado ? 'bg-red-500/20 text-red-600 hover:bg-red-500/30' : 'bg-sky-500/20 text-sky-600 hover:bg-sky-500/30' }}"
                                            data-modulo-id="{{ $modulo->id }}">
                                            @if($modulo->bomba_estado)
                                                💧 Apagar Bomba
                                            @else
                                                💧 Encender Bomba
                                            @endif
                                        </button>

                                        @if($modulo->estado == 'Disponible')
                                            <a href="{{ route('admin.viveros.modulos.startCultivoForm', [$vivero, $modulo]) }}" class="inline-flex items-center px-3 py-1.5 bg-[#96d900]/20 text-[#6b9b00] rounded-lg text-xs font-semibold hover:bg-[#96d900]/30 transition">
                                                🌱 Iniciar
                                            </a>
                                        @endif

                                        <a href="{{ route('admin.viveros.modulos.edit', [$vivero, $modulo]) }}" class="inline-flex items-center px-3 py-1.5 bg-[#ffdef0] text-[#9c0000] rounded-lg text-xs font-semibold hover:bg-[#ffcce0] transition">✏️ Editar</a>

                                        <form action="{{ route('admin.viveros.modulos.destroy', [$vivero, $modulo]) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500/20 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-500/30 transition">🗑️ Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-[#999999]">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-2xl">📭</span>
                                        <p>Este vivero aún no tiene módulos.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ====================================================== --}}
    {{-- SCRIPT PARA LA TABLA DE MONITOREO (MODIFICADO PARA LUZ Y HUMEDAD) --}}
    {{-- ====================================================== --}}
    <script>
        // Declaramos el intervalo en un ámbito superior para poder detenerlo
        let monitoreoInterval;

        // ... (Funciones getAlertStyles, formatNumber, formatTimeAgo - sin cambios) ...
        function getAlertStyles(estado) {
            estado = estado || 'Sin Lecturas';
            switch (estado) {
                case 'CRÍTICO': return { text: 'text-white', bg: 'bg-red-600 font-semibold' };
                case 'ADVERTENCIA': return { text: 'text-amber-900', bg: 'bg-amber-400 font-semibold' };
                case 'OFFLINE': return { text: 'text-[#999999]', bg: 'bg-[#e0e0e0] font-semibold' };
                case 'Sin Lecturas': return { text: 'text-[#999999]', bg: 'bg-[#e0e0e0] font-semibold' };
                case 'OK': default: return { text: 'text-white', bg: 'bg-[#96d900] font-semibold' };
            }
        }
        function formatNumber(value, decimals) {
            if (value === null || typeof value === 'undefined') return '---';
            const numberValue = parseFloat(value);
            if (!isNaN(numberValue)) { return numberValue.toFixed(decimals); }
            return '---';
        }
        function formatTimeAgo(minutes, lastHour) {
             if (minutes === null || typeof minutes === 'undefined') { return 'Nunca'; }
             if (minutes < 1) { return 'Hace instantes'; }
             if (minutes < 60) { return `Hace ${minutes} min`; }
             if (minutes < 120) { return 'Hace 1 hora'; }
             if (minutes < 1440) { return `Hace ${Math.floor(minutes / 60)} horas`; }
             const days = Math.floor(minutes / 1440);
             return `Hace ${days} día${days > 1 ? 's' : ''}`;
        }

        // Función principal para buscar y dibujar la tabla
        function fetchAndRenderOwnerTable() {
            const container = document.getElementById('monitoreo-table-container');
            if (!container) return;
            
            const viveroId = {{ $vivero->id }};
            const apiUrl = window.location.origin + `/admin/vivero/${viveroId}/latest-data`;

            if (container.querySelector('table')) {
                 container.querySelector('tbody').classList.add('opacity-50', 'transition-opacity');
            } else {
                container.innerHTML = `<p class="text-gray-400 animate-pulse">Refrescando datos...</p>`;
            }

            fetch(apiUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.status === 419 || response.status === 401) {
                    clearInterval(monitoreoInterval); 
                    container.innerHTML = `<p class="text-yellow-400 font-bold">Tu sesión ha expirado. Redirigiendo...</p>`;
                    window.location.reload(); 
                    throw new Error('Sesión expirada.'); 
                }
                if (response.status === 403) throw new Error('No autorizado.');
                if (!response.ok) throw new Error('Error API: ' + response.statusText);
                return response.json(); 
            })
            .then(data => {
                if (data.message) throw new Error(data.message);
                if (data.length === 0) {
                    container.innerHTML = `<p class="text-[#999999]">Este vivero no tiene módulos activos (en estado 'Ocupado').</p>`;
                    return;
                }

                // --- MODIFICACIÓN: AÑADIR NUEVAS CABECERAS ---
                let tableHtml = `
                    <div class="relative overflow-x-auto rounded-xl border border-[#e0e0e0]">
                    <table class="w-full text-sm">
                        <thead class="bg-gradient-to-r from-[#fafafa] to-[#f5f5f5] border-b border-[#e0e0e0]">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Módulo</th>
                                <th class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Cultivo</th>
                                <th class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">PH</th>
                                <th class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">EC</th>
                                <th class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Temp (°C)</th>
                                <th class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Luz (lux)</th>
                                <th class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Humedad (%)</th>
                                <th class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Estado</th>
                                <th class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Último Reporte</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e0e0e0]">`;
                // --- FIN MODIFICACIÓN ---

                data.forEach(item => {
                    const styles = getAlertStyles(item.estado_alerta);
                    const isOffline = item.estado_alerta === 'OFFLINE' || item.estado_alerta === 'Sin Lecturas';
                    
                    const tiempoReporte = formatTimeAgo(item.minutos_offline, item.ultima_lectura); // Usamos el nombre corregido
                    const phDisplay = formatNumber(item.ph, 2);
                    const ecDisplay = formatNumber(item.ec, 2);
                    const tempDisplay = formatNumber(item.temperatura, 1);
                    
                    // --- MODIFICACIÓN: LEER NUEVOS DATOS ---
                    const luzDisplay = formatNumber(item.luz, 0);
                    const humedadDisplay = formatNumber(item.humedad, 1);
                    // --- FIN MODIFICACIÓN ---

                    // --- MODIFICACIÓN: AÑADIR CELDAS A LA FILA ---
                    tableHtml += `
                        <tr class="hover:bg-[#ffdef0]/30 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-[#1a1a1a]">🔧 ${item.codigo}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-[#555555]">${item.cultivo || 'Sin asignar'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-[#555555]">${phDisplay}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-[#555555]">${ecDisplay}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-[#555555]">${tempDisplay}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-[#555555]">${luzDisplay}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-[#555555]">${humedadDisplay}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${styles.bg} ${styles.text}">${item.estado_alerta}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-[#999999]">${tiempoReporte}</td>
                        </tr>`;
                    // --- FIN MODIFICACIÓN ---
                });

                tableHtml += `</tbody></table></div>`;
                container.innerHTML = tableHtml;
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.message !== 'Sesión expirada.') {
                    container.innerHTML = `<p class="text-red-400">Error al cargar la tabla: ${error.message}.</p>`;
                }
            });
        }

        // --- Carga inicial y refresco automático ---
        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('monitoreo-table-container')) {
                fetchAndRenderOwnerTable(); 
                monitoreoInterval = setInterval(fetchAndRenderOwnerTable, 30000); 
            }
        });
    </script>

    {{-- ====================================================== --}}
    {{-- SCRIPT PARA EL CONTROL DE LA BOMBA --}}
    {{-- ====================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleButtons = document.querySelectorAll('.bomba-toggle-btn');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const moduloId = this.dataset.moduloId;
                    const url = `/modulos/${moduloId}/toggle-bomba`; // Usamos la ruta directa

                    // Deshabilitar botón para evitar clics múltiples
                    this.disabled = true;
                    this.innerHTML = 'Cambiando...';

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Actualizar la UI del botón
                        const isBombaOn = data.bomba_estado;
                        this.innerHTML = isBombaOn ? '💧 Apagar Bomba' : '💧 Encender Bomba';

                        // Quitar clases viejas y añadir las nuevas
                        this.classList.remove('bg-red-500/20', 'text-red-600', 'hover:bg-red-500/30', 'bg-sky-500/20', 'text-sky-600', 'hover:bg-sky-500/30');
                        
                        if (isBombaOn) {
                            this.classList.add('bg-red-500/20', 'text-red-600', 'hover:bg-red-500/30');
                        } else {
                            this.classList.add('bg-sky-500/20', 'text-sky-600', 'hover:bg-sky-500/30');
                        }
                    })
                    .catch(error => {
                        console.error('Error al cambiar estado de la bomba:', error);
                        // Revertir el texto del botón en caso de error
                        // (Una implementación más robusta podría leer el estado original)
                        this.innerHTML = 'Error';
                    })
                    .finally(() => {
                        // Volver a habilitar el botón
                        this.disabled = false;
                    });
                });
            });
        });
    </script>
</x-app-layout>