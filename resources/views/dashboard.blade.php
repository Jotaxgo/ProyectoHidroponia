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

            {{-- Contenedor principal para la tabla y el panel de alertas --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{ 
                isModalOpen: false, 
                modalData: {},
                openModuleModal(row) {
                    const data = row.dataset;
                    this.modalData = {
                        nombre_modulo: data.nombre_modulo,
                        cultivo_actual: data.cultivo_actual,
                        luz: data.luz,
                        humedad: data.humedad,
                        tiempo_reporte: data.tiempo_reporte,
                        detail_url: data.detail_url
                    };
                    this.isModalOpen = true;
                }
            }">

                {{-- Columna principal para la tabla de monitoreo (ocupa 2/3 del espacio) --}}
                <div class="lg:col-span-2">
                    <div class="bg-white/90 backdrop-filter backdrop-blur-lg rounded-2xl p-8 border border-[#e0e0e0]" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent mb-6">📡 Monitoreo Rápido de Módulos</h2>
                        <div id="monitoreo-table-container" class="overflow-x-auto relative">
                            <p class="text-[#999999] animate-pulse">Cargando datos de módulos...</p>
                        </div>
                    </div>
                </div>

                {{-- Columna lateral para el panel de alertas (ocupa 1/3 del espacio) --}}
                <div class="lg:col-span-1">
                    <div class="bg-white/90 backdrop-filter backdrop-blur-lg rounded-2xl p-8 border border-[#e0e0e0]" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent mb-6">⚠️ Panel de Alertas</h2>
                        <div id="admin-alerts-panel" class="space-y-3">
                            <p class="text-[#999999] animate-pulse">Cargando alertas...</p>
                        </div>
                    </div>
                </div>

                {{-- VENTANA MODAL PARA DETALLES DEL MÓDULO --}}
                <div x-show="isModalOpen" x-cloak class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[999]" @click.self="isModalOpen = false">
                    <div x-show="isModalOpen" x-transition class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 m-4">
                        <h3 class="text-2xl font-bold text-[#1a1a1a] mb-2" x-text="modalData.nombre_modulo"></h3>
                        <p class="text-sm text-[#555555] mb-6">Cultivo: <span class="font-semibold" x-text="modalData.cultivo_actual || 'No asignado'"></span></p>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                            <div class="bg-gray-100 p-3 rounded-lg">
                                <p class="text-gray-500">💡 Nivel de Luz</p>
                                <p class="font-bold text-lg text-[#1a1a1a]" x-text="modalData.luz ? modalData.luz + ' lux' : '---'"></p>
                            </div>
                            <div class="bg-gray-100 p-3 rounded-lg">
                                <p class="text-gray-500">💧 Humedad</p>
                                <p class="font-bold text-lg text-[#1a1a1a]" x-text="modalData.humedad ? modalData.humedad + ' %' : '---'"></p>
                            </div>
                            <div class="bg-gray-100 p-3 rounded-lg col-span-2">
                                <p class="text-gray-500">📅 Último Reporte</p>
                                <p class="font-bold text-lg text-[#1a1a1a]" x-text="modalData.tiempo_reporte"></p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-[#e0e0e0]">
                            <button @click="isModalOpen = false" class="text-[#555555] hover:text-[#9c0000] transition font-medium text-sm">Cerrar</button>
                            <a :href="modalData.detail_url" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                                Ver Gráficos e Historial →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let adminMonitoreoInterval;

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

        function fetchAndRenderAlertsPanel() {
            const container = document.getElementById('admin-alerts-panel');
            if (!container) return;
            fetch(`{{ route('admin.dashboard.activeAlerts') }}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }})
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    container.innerHTML = `<div class="text-center p-4 bg-green-500/10 rounded-lg"><p class="text-green-700 font-semibold">✅ ¡Todo en orden!</p><p class="text-sm text-green-600">No hay alertas activas.</p></div>`;
                    return;
                }
                let alertsHtml = '';
                data.forEach(item => {
                    const styles = getAlertStyles(item.estado_alerta);
                    alertsHtml += `<a href="${window.location.origin}/admin/modulos/${item.modulo_id}/detail" class="block p-3 rounded-lg hover:bg-gray-100 transition"><div class="flex justify-between items-center"><span class="font-semibold text-sm text-[#1a1a1a]">${item.nombre_modulo}</span><span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full ${styles.bg} ${styles.text}">${item.estado_alerta}</span></div><p class="text-xs text-[#555555] mt-1">Cultivo: ${item.cultivo_actual || 'N/A'}</p></a>`;
                });
                container.innerHTML = alertsHtml;
            })
            .catch(error => {
                console.error('Error en el panel de alertas:', error);
                container.innerHTML = `<p class="text-red-600 font-semibold text-sm">❌ No se pudo cargar el panel de alertas.</p>`;
            });
        }
        
        function fetchAndRenderAdminTable() {
            const container = document.getElementById('monitoreo-table-container');
            if (!container) return; 
            if (container.querySelector('table')) {
                 container.querySelector('tbody').classList.add('opacity-50', 'transition-opacity');
            }
            fetch(`{{ route('admin.dashboard.latest-data') }}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }})
            .then(response => {
                if (response.status === 419 || response.status === 401) { window.location.reload(); throw new Error('Sesión expirada.'); }
                if (!response.ok) throw new Error('Error API: ' + response.statusText);
                return response.json(); 
            })
            .then(data => {
                if (data.length === 0) {
                    container.innerHTML = `<p class="text-[#999999]">📭 No hay módulos ocupados con datos.</p>`;
                    return;
                }
                let tableHtml = `<table class="min-w-full divide-y divide-[#e0e0e0]">
                        <thead class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">🔧 Módulo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">pH</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">EC</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">🌡️ Temp (°C)</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">⚠️ Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e0e0e0]">`;
                data.forEach(item => {
                    const styles = getAlertStyles(item.estado_alerta);
                    tableHtml += `
                        <tr class="hover:bg-[#ffdef0]/30 transition duration-150 cursor-pointer" 
                            @click="openModuleModal($event.currentTarget)"
                            data-nombre_modulo="${item.nombre_modulo || `ID:${item.modulo_id}`}"
                            data-cultivo_actual="${item.cultivo_actual || ''}"
                            data-luz="${formatNumber(item.luz, 0)}"
                            data-humedad="${formatNumber(item.humedad, 1)}"
                            data-tiempo_reporte="${formatTimeAgo(item.minutos_offline, item.hora_ultima_lectura)}"
                            data-detail_url="${window.location.origin}/admin/modulos/${item.modulo_id}/detail">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#1a1a1a]">${item.nombre_modulo || `ID:${item.modulo_id}`}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#555555]">${formatNumber(item.ph, 2)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#555555]">${formatNumber(item.ec, 2)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#555555]">${formatNumber(item.temperatura, 1)}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full ${styles.bg} ${styles.text}">
                                    ${item.estado_alerta || 'Sin Lecturas'}
                                </span>
                            </td>
                        </tr>`;
                });
                tableHtml += `</tbody></table>`;
                container.innerHTML = tableHtml;
            })
            .catch(error => {
                console.error('Error en la tabla de monitoreo:', error);
                if (error.message !== 'Sesión expirada.') {
                    container.innerHTML = `<p class="text-red-600 font-semibold">❌ Error al cargar la tabla.</p>`; 
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('monitoreo-table-container')) {
                 fetchAndRenderAdminTable();
                 fetchAndRenderAlertsPanel();
                 adminMonitoreoInterval = setInterval(() => {
                    fetchAndRenderAdminTable();
                    fetchAndRenderAlertsPanel();
                 }, 30000); 
            }
        });
    </script>
</x-app-layout>