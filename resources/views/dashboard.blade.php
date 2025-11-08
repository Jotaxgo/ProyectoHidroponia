<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            📊 <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Dashboard del Administrador</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Sección de Estadísticas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white/90 backdrop-filter backdrop-blur-lg rounded-2xl p-6 border border-[#e0e0e0]" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                    <div>
                        <p class="text-sm font-medium text-[#555555] mb-2">👤 Total de Usuarios</p>
                        <p class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
                <div class="bg-white/90 backdrop-filter backdrop-blur-lg rounded-2xl p-6 border border-[#e0e0e0]" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                    <div>
                        <p class="text-sm font-medium text-[#555555] mb-2">🌱 Total de Viveros</p>
                        <p class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">{{ $stats['total_viveros'] }}</p>
                    </div>
                </div>
                <div class="bg-white/90 backdrop-filter backdrop-blur-lg rounded-2xl p-6 border border-[#e0e0e0]" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                    <div>
                        <p class="text-sm font-medium text-[#555555] mb-2">🔧 Total de Módulos</p>
                        <p class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">{{ $stats['total_modulos'] }}</p>
                    </div>
                </div>
                <div class="bg-white/90 backdrop-filter backdrop-blur-lg rounded-2xl p-6 border border-[#e0e0e0]" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                    <div>
                        <p class="text-sm font-medium text-[#555555] mb-2">🌾 Módulos Ocupados</p>
                        <p class="text-3xl font-bold text-[#96d900]">{{ $stats['modulos_ocupados'] }}</p>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN DE MONITOREO DE SENSORES --}}
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg rounded-2xl p-8 border border-[#e0e0e0]" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent mb-6">📡 Estado General de Módulos Ocupados</h2>
                
                {{-- Contenedor con overflow-x-auto para responsividad de la tabla --}}
                <div id="monitoreo-table-container" class="overflow-x-auto relative">
                    <p class="text-[#999999] animate-pulse">Cargando datos de módulos...</p>
                </div>
            </div>

        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- BLOQUE DE SCRIPTS COMPLETO Y CORREGIDO --}}
    {{-- ========================================================= --}}
    <script>
        let adminMonitoreoInterval;

        // Funciones auxiliares (getAlertStyles, formatNumber, formatTimeAgo)
        function getAlertStyles(estado) {
            estado = estado || 'Sin Lecturas';
            switch (estado) {
                case 'CRÍTICO': return { text: 'text-white', bg: 'bg-red-600 font-semibold' };
                case 'ADVERTENCIA': return { text: 'text-yellow-900', bg: 'bg-yellow-400 font-semibold' };
                case 'OFFLINE': return { text: 'text-white', bg: 'bg-gray-500 font-semibold' };
                case 'Sin Lecturas': return { text: 'text-white', bg: 'bg-gray-500 font-semibold' };
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
        
        /**
         * Genera el contenido de la tabla de monitoreo del Admin.
         */
        function fetchAndRenderAdminTable() {
            const container = document.getElementById('monitoreo-table-container');
            if (!container) return; 
            
            const apiUrl = window.location.origin + '/admin/dashboard/latest-data'; // Ruta web

            if (container.querySelector('table')) {
                 container.querySelector('tbody').classList.add('opacity-50', 'transition-opacity');
            } else {
                container.innerHTML = `<p class="text-[#999999] animate-pulse">Refrescando datos...</p>`;
            }

            fetch(apiUrl, {
                headers: { 
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                // Manejo de sesión expirada
                if (response.status === 419 || response.status === 401) {
                    clearInterval(adminMonitoreoInterval); 
                    container.innerHTML = `<p class="text-yellow-600 font-bold">Tu sesión ha expirado. Redirigiendo...</p>`;
                    window.location.reload(); 
                    throw new Error('Sesión expirada.'); 
                }
                if (!response.ok) throw new Error('Error API: ' + response.statusText);
                return response.json(); 
            })
            .then(data => {
                if (!Array.isArray(data)) throw new Error('Respuesta API inválida.');
                if (data.length === 0) {
                    container.innerHTML = `<p class="text-[#999999]">📭 No hay módulos ocupados con datos.</p>`;
                    return;
                }

                // Construcción de la tabla HTML (con las 10 columnas)
                let tableHtml = `
                    <table class="min-w-full divide-y divide-[#e0e0e0]">
                        <thead class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">🔧 Módulo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">🌱 Cultivo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">pH</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">EC</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">🌡️ Temp (°C)</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">💡 Luz (lux)</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">💧 Humedad (%)</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">⚠️ Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">📅 Último Reporte</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">⚙️ Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e0e0e0]">
                `;

                // Loop para cada fila
                data.forEach(item => {
                    const codigoModulo = item.nombre_modulo || `ID:${item.modulo_id}`;
                    const cultivoActual = item.cultivo_actual || '<span class="italic text-[#999999]">Sin asignar</span>'; 
                    const estadoAlerta = item.estado_alerta || 'Sin Lecturas'; 
                    const styles = getAlertStyles(estadoAlerta);
                    const tiempoReporte = formatTimeAgo(item.minutos_offline, item.hora_ultima_lectura);
                    const isOfflineOrNoData = estadoAlerta === 'OFFLINE' || estadoAlerta === 'Sin Lecturas';

                    const phDisplay = formatNumber(item.ph, 2);
                    const ecDisplay = formatNumber(item.ec, 2);
                    const tempDisplay = formatNumber(item.temperatura, 1);
                    const luzDisplay = formatNumber(item.luz, 0); 
                    const humedadDisplay = formatNumber(item.humedad, 1); 

                    tableHtml += `
                        <tr class="bg-white hover:bg-[#ffdef0]/30 transition duration-150 border-b border-[#e0e0e0]">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#1a1a1a]">${codigoModulo}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#555555]">${cultivoActual}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#555555]">${phDisplay}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#555555]">${ecDisplay}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#555555]">${tempDisplay}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#555555]">${luzDisplay}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#555555]">${humedadDisplay}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full ${styles.bg} ${styles.text}">
                                    ${estadoAlerta}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#999999]">${tiempoReporte}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="${window.location.origin}/admin/modulos/${item.modulo_id}/detail" class="text-[#ff4b65] hover:text-[#9c0000] font-semibold transition">Ver Detalle →</a>
                            </td>
                        </tr>
                    `;
                }); // Fin del forEach

                tableHtml += `</tbody></table>`;
                container.innerHTML = tableHtml;

            }) // Fin del .then(data => ...)
            .catch(error => {
                console.error('Error en la obtención de datos (Admin Dashboard):', error);
                if (error.message !== 'Sesión expirada.') {
                    container.innerHTML = `<p class="text-red-600 font-semibold">❌ Error al cargar la tabla: ${error.message}.</p>`; 
                }
            });
        } // Fin de fetchAndRenderAdminTable

        // Carga inicial y refresco automático
        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('monitoreo-table-container')) {
                 fetchAndRenderAdminTable();
                 adminMonitoreoInterval = setInterval(fetchAndRenderAdminTable, 30000); 
            }
        });
    </script>
</x-app-layout>