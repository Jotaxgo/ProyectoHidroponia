<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Gestionar Módulos del Vivero: <span class="text-hydro-accent-gold">{{ $vivero->nombre }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- GESTIÓN DE MÓDULOS --}}
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                <div class="flex justify-between items-center mb-8">
                    <div class="flex flex-col gap-2">
                        @if(Auth::user()->role->nombre_rol == 'Admin')
                            <a href="{{ route('admin.viveros.index') }}" class="text-[#555555] hover:text-[#9c0000] transition font-medium text-sm inline-block">← Volver a Viveros</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-[#555555] hover:text-[#9c0000] transition font-medium text-sm inline-block">← Volver a Dashboard</a>
                        @endif
                    </div>
                                        <div class="flex gap-4">
                                            <a href="{{ route('admin.viveros.modulos.trash', $vivero) }}" class="text-[#555555] hover:text-[#9c0000] transition font-medium text-sm">🗑️ Papelera</a>
                                            <a href="{{ route('admin.viveros.settings.edit', $vivero) }}" class="text-[#555555] hover:text-[#9c0000] transition font-medium text-sm">⚙️ Ajustes de Sensores</a>
                                            <a href="{{ route('admin.viveros.modulos.create', $vivero) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white rounded-lg text-xs font-semibold hover:shadow-lg transition">
                                                + Nuevo Módulo
                                            </a>
                                        </div>
                </div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent mb-6">Gestión de Módulos</h2>
                <div id="gestion-table-container" class="relative overflow-x-auto rounded-xl border border-[#e0e0e0]">
                    <table class="w-full text-sm">
                        <thead class="bg-gradient-to-r from-[#fafafa] to-[#f5f5f5] border-b border-[#e0e0e0]">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Código</th>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Device ID</th>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Estado</th>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Estado Bomba</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e0e0e0]">
                            @forelse ($modulos as $modulo)
                            <tr id="gestion-row-{{ $modulo->id }}" data-modulo-id="{{ $modulo->id }}" class="hover:bg-gray-50 transition">
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
                                <td class="px-6 py-4" id="bomba-status-{{ $modulo->id }}">
                                    @if($modulo->bomba_estado)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-800"><span class="w-2 h-2 mr-2 bg-gray-500 rounded-full"></span>Apagada</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-200 text-green-800"><span class="w-2 h-2 mr-2 bg-green-500 rounded-full"></span>Encendida</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2 flex-wrap">
                                        <button title="{{ $modulo->bomba_estado ? 'Encender Bomba' : 'Apagar Bomba' }}" class="bomba-toggle-btn inline-flex items-center justify-center w-10 h-10 rounded-full transition {{ $modulo->bomba_estado ? 'bg-green-200 text-green-800 hover:bg-green-300' : 'bg-red-200 text-red-800 hover:bg-red-300' }}" data-modulo-id="{{ $modulo->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9" /></svg>
                                        </button>
                                        @if($modulo->estado == 'Disponible')
                                            <a href="{{ route('admin.viveros.modulos.startCultivoForm', [$vivero, $modulo]) }}" class="inline-flex items-center px-3 py-1.5 bg-[#96d900]/20 text-[#6b9b00] rounded-lg text-xs font-semibold hover:bg-[#96d900]/30 transition">🌱 Iniciar</a>
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
                            <tr><td colspan="5" class="px-6 py-8 text-center text-[#999999]"><div class="flex flex-col items-center gap-2"><span class="text-2xl">📭</span><p>Este vivero aún no tiene módulos.</p></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- MONITOREO EN TIEMPO REAL --}}
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">📊 Monitoreo en Tiempo Real</h2>
                    <div id="last-updated-container" class="text-sm text-gray-500"></div>
                </div>
                <div id="monitoreo-table-container"><p class="text-[#999999] animate-pulse">Cargando datos de módulos...</p></div>
            </div>
        </div>
    </div>

    {{-- MODAL DE HISTORIAL --}}
    <div id="history-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center" style="display: none;">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-4 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modal-title" class="text-xl font-bold text-gray-800">Historial del Módulo</h3>
                <button id="modal-close-btn" class="text-gray-500 hover:text-gray-800 text-3xl leading-none">&times;</button>
            </div>
            <div id="modal-content" class="h-96">
                <canvas id="history-chart"></canvas>
            </div>
        </div>
    </div>

    <style>
        @keyframes row-flash {
            0% { background-color: rgba(250, 204, 21, 0.4); }
            100% { background-color: transparent; }
        }
        .row-updated { animation: row-flash 1.5s ease-out; }
        .highlight-row { background-color: rgba(255, 237, 213, 0.7) !important; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script>
        const sensorLimits = @json($limits);
        let charts = {};
        let historyChart = null;

        const centerTextPlugin = {
            id: 'centerText',
            afterDraw: (chart) => {
                if (!chart.options.plugins.centerText || !chart.options.plugins.centerText.text) return;
                const ctx = chart.ctx;
                const text = chart.options.plugins.centerText.text;
                const color = chart.options.plugins.centerText.color || '#000';
                const font = chart.options.plugins.centerText.font || '1rem sans-serif';
                const centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
                const centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2;
                ctx.save();
                ctx.font = font;
                ctx.fillStyle = color;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(text, centerX, centerY);
                ctx.restore();
            }
        };
        Chart.register(centerTextPlugin);

        function createOrUpdateGauge(sensor, id, value) {
            const limits = sensorLimits[sensor];
            if (!limits) {
                console.warn(`Límites no definidos para el sensor: ${sensor}`);
                return;
            }

            const canvasId = `${sensor}-gauge-${id}`;
            const container = document.getElementById(`${sensor}-gauge-container-${id}`);
            if (!container) return;

            if (!document.getElementById(canvasId)) {
                container.innerHTML = `<canvas id="${canvasId}" width="80" height="80"></canvas>`;
            }
            
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            let color = '#4ade80';
            if (value === null) { color = '#e5e7eb'; } 
            else if (value < limits.min || value > limits.max) { color = '#f87171'; } 
            else if ((value > limits.min && value < (limits.min + (limits.max - limits.min) * 0.15)) || (value < limits.max && value > (limits.max - (limits.max - limits.min) * 0.15))) { color = '#facc15';}

            const data = { datasets: [{ data: [value, limits.max - value], backgroundColor: [color, '#e5e7eb'], borderWidth: 0, circumference: 270, rotation: 225 }] };
            const chartId = `${sensor}-${id}`;
            if (charts[chartId]) {
                charts[chartId].data.datasets[0].data = data.datasets[0].data;
                charts[chartId].data.datasets[0].backgroundColor[0] = color;
                charts[chartId].options.plugins.centerText.text = value !== null ? formatNumber(value, sensor === 'ph' ? 2 : 1) : '---';
                charts[chartId].update('none');
            } else {
                charts[chartId] = new Chart(ctx, {
                    type: 'doughnut', data: data,
                    options: { responsive: true, maintainAspectRatio: false, cutout: '75%', animation: false,
                        plugins: { legend: { display: false }, tooltip: { enabled: false },
                            centerText: { text: value !== null ? formatNumber(value, sensor === 'ph' ? 2 : 1) : '---', color: '#1f2937', font: 'bold 1rem sans-serif' }
                        }
                    }
                });
            }
        }
        
        function getAlertStyles(estado) {
             estado = estado || 'Sin Lecturas';
            switch (estado) {
                case 'CRÍTICO': return { text: 'text-white', bg: 'bg-red-500' };
                case 'ADVERTENCIA': return { text: 'text-yellow-800', bg: 'bg-yellow-400' };
                case 'OFFLINE': return { text: 'text-gray-800', bg: 'bg-gray-300' };
                case 'Sin Lecturas': return { text: 'text-gray-800', bg: 'bg-gray-300' };
                case 'OK': default: return { text: 'text-green-800', bg: 'bg-green-300' };
            }
        }
        function formatNumber(value, decimals) {
            if (value === null || typeof value === 'undefined') return '---';
            const numberValue = parseFloat(value);
            return !isNaN(numberValue) ? numberValue.toFixed(decimals) : '---';
        }
        function formatTimeAgo(minutes) {
             if (minutes === null || typeof minutes === 'undefined') { return 'Nunca'; }
             if (minutes < 1) { return 'Hace instantes'; }
             if (minutes < 60) { return `Hace ${minutes} min`; }
             if (minutes < 1440) { return `Hace ${Math.floor(minutes / 60)}h`; }
             const days = Math.floor(minutes / 1440);
             return `Hace ${days} día${days > 1 ? 's' : ''}`;
        }

        function updateTableContent(data) {
             data.forEach(item => {
                const row = document.getElementById(`monitoreo-row-${item.modulo_id}`);
                if (!row) return;
                const statusCell = row.querySelector('.status-cell');
                const reportCell = row.querySelector('.report-cell');
                const alertStyle = getAlertStyles(item.estado_alerta);
                statusCell.innerHTML = `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${alertStyle.bg} ${alertStyle.text}">${alertStyle.text.includes('white') ? item.estado_alerta : item.estado_alerta}</span>`;
                reportCell.textContent = formatTimeAgo(item.minutos_offline);
                createOrUpdateGauge('ph', item.modulo_id, item.ph);
                createOrUpdateGauge('ec', item.modulo_id, item.ec);
                createOrUpdateGauge('temperatura', item.modulo_id, item.temperatura);
                createOrUpdateGauge('luz', item.modulo_id, item.luz);
                createOrUpdateGauge('humedad', item.modulo_id, item.humedad);
                row.classList.remove('row-updated');
                void row.offsetWidth;
                row.classList.add('row-updated');
            });
            const lastUpdatedContainer = document.getElementById('last-updated-container');
            if(lastUpdatedContainer) {
                lastUpdatedContainer.innerHTML = `Última actualización: <strong>${new Date().toLocaleTimeString()}</strong>`;
            }
        }

        function buildTableShell(container, data) {
            let tableHtml = `
                <div class="relative overflow-x-auto rounded-xl border border-gray-200">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Módulo</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Estado</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">PH</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">EC</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Temp (°C)</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Luz (lux)</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Humedad (%)</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Último Reporte</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Historial</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">`;
            data.forEach(item => {
                tableHtml += `
                    <tr id="monitoreo-row-${item.modulo_id}" data-modulo-id="${item.modulo_id}" class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 whitespace-nowrap"><div class="font-semibold text-gray-800">🔧 ${item.codigo}</div><div class="text-xs text-gray-500">${item.cultivo || 'Sin asignar'}</div></td>
                        <td class="status-cell px-4 py-3 whitespace-nowrap"></td>
                        <td class="px-4 py-3"><div class="w-20 h-20 mx-auto" id="ph-gauge-container-${item.modulo_id}"></div></td>
                        <td class="px-4 py-3"><div class="w-20 h-20 mx-auto" id="ec-gauge-container-${item.modulo_id}"></div></td>
                        <td class="px-4 py-3"><div class="w-20 h-20 mx-auto" id="temperatura-gauge-container-${item.modulo_id}"></div></td>
                        <td class="px-4 py-3"><div class="w-20 h-20 mx-auto" id="luz-gauge-container-${item.modulo_id}"></div></td>
                        <td class="px-4 py-3"><div class="w-20 h-20 mx-auto" id="humedad-gauge-container-${item.modulo_id}"></div></td>
                        <td class="report-cell px-4 py-3 whitespace-nowrap text-gray-500"></td>
                        <td class="px-4 py-3 text-center">
                            <button class="history-btn p-2 rounded-full hover:bg-gray-200 transition" title="Ver historial" data-modulo-id="${item.modulo_id}" data-modulo-codigo="${item.codigo}">
                                📈
                            </button>
                        </td>
                    </tr>`;
            });
            tableHtml += `</tbody></table></div>`;
            container.innerHTML = tableHtml;
        }
        
        function setupInteractiveHighlighting() {
            const handleHighlight = (moduloId, add) => {
                const gestionRow = document.getElementById(`gestion-row-${moduloId}`);
                const monitoreoRow = document.getElementById(`monitoreo-row-${moduloId}`);
                if (gestionRow) gestionRow.classList.toggle('highlight-row', add);
                if (monitoreoRow) monitoreoRow.classList.toggle('highlight-row', add);
            };
            document.getElementById('gestion-table-container').addEventListener('mouseover', (event) => {
                const row = event.target.closest('tr[data-modulo-id]');
                if(row) handleHighlight(row.dataset.moduloId, true);
            });
            document.getElementById('gestion-table-container').addEventListener('mouseout', (event) => {
                const row = event.target.closest('tr[data-modulo-id]');
                if(row) handleHighlight(row.dataset.moduloId, false);
            });
            document.getElementById('monitoreo-table-container').addEventListener('mouseover', (event) => {
                const row = event.target.closest('tr[data-modulo-id]');
                if(row) handleHighlight(row.dataset.moduloId, true);
            });
            document.getElementById('monitoreo-table-container').addEventListener('mouseout', (event) => {
                const row = event.target.closest('tr[data-modulo-id]');
                if(row) handleHighlight(row.dataset.moduloId, false);
            });
        }

        function setupHistoryModal() {
            const modal = document.getElementById('history-modal');
            const closeBtn = document.getElementById('modal-close-btn');
            const modalTitle = document.getElementById('modal-title');
            const modalContent = document.getElementById('modal-content');
            modal.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });
            closeBtn.addEventListener('click', () => modal.style.display = 'none');
            
            document.getElementById('monitoreo-table-container').addEventListener('click', function(event) {
                const historyBtn = event.target.closest('.history-btn');
                if (!historyBtn) return;

                const moduloId = historyBtn.dataset.moduloId;
                const moduloCodigo = historyBtn.dataset.moduloCodigo;

                modalTitle.textContent = `Historial de 24h para Módulo: ${moduloCodigo}`;
                modal.style.display = 'flex';
                modalContent.innerHTML = '<p class="text-center animate-pulse">Cargando historial...</p>';
                if(historyChart) historyChart.destroy();

                fetch(`/admin/modulos/${moduloId}/history`)
                    .then(response => response.ok ? response.json() : Promise.reject('Error al cargar historial'))
                    .then(data => {
                        if (data.length === 0) {
                            modalContent.innerHTML = '<p class="text-center text-gray-500">No hay datos históricos para este módulo en las últimas 24 horas.</p>';
                            return;
                        }
                        
                        modalContent.innerHTML = '<canvas id="history-chart"></canvas>';
                        const ctx = document.getElementById('history-chart').getContext('2d');
                        
                        historyChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.map(d => d.created_at),
                                datasets: [
                                    { label: 'PH', data: data.map(d => d.ph), borderColor: 'rgba(59, 130, 246, 1)', yAxisID: 'yPH', tension: 0.3 },
                                    { label: 'EC', data: data.map(d => d.ec), borderColor: 'rgba(234, 179, 8, 1)', yAxisID: 'yEC', tension: 0.3 },
                                    { label: 'Temp', data: data.map(d => d.temperatura), borderColor: 'rgba(239, 68, 68, 1)', yAxisID: 'yTemp', tension: 0.3 },
                                    { label: 'Luz', data: data.map(d => d.luz), borderColor: 'rgba(251, 191, 36, 1)', yAxisID: 'yLuz', tension: 0.3 },
                                    { label: 'Humedad', data: data.map(d => d.humedad), borderColor: 'rgba(134, 239, 172, 1)', yAxisID: 'yHum', tension: 0.3 }
                                ]
                            },
                            options: { responsive: true, maintainAspectRatio: false,
                                scales: {
                                    x: { type: 'time', time: { unit: 'hour', tooltipFormat: 'HH:mm dd/MM', displayFormats: { hour: 'HH:mm' } }, title: { display: true, text: 'Tiempo' } },
                                    yPH: { type: 'linear', display: true, position: 'left', title: { display: true, text: 'PH' } },
                                    yEC: { type: 'linear', display: true, position: 'right', title: { display: true, text: 'EC' }, grid: { drawOnChartArea: false } },
                                    yTemp: { type: 'linear', display: true, position: 'right', title: { display: true, text: 'Temp (°C)' }, offset: true, grid: { drawOnChartArea: false } },
                                    yLuz: { type: 'linear', display: true, position: 'left', title: { display: true, text: 'Luz (lux)' }, grid: { drawOnChartArea: false }, beginAtZero: true },
                                    yHum: { type: 'linear', display: true, position: 'right', title: { display: true, text: 'Humedad (%)' }, grid: { drawOnChartArea: false }, beginAtZero: true },
                                }
                            }
                        });
                    })
                    .catch(error => {
                        console.error(error);
                        modalContent.innerHTML = '<p class="text-center text-red-500">No se pudo cargar el historial.</p>';
                    });
            });
        }

        function fetchAndRenderOwnerTable() {
            const container = document.getElementById('monitoreo-table-container');
            if (!container) return;
            const viveroId = {{ $vivero->id }};
            const apiUrl = window.location.origin + `/admin/vivero/${viveroId}/latest-data`;

            fetch(apiUrl)
            .then(response => {
                if (!response.ok) throw new Error('Error en la respuesta de la API: ' + response.statusText);
                return response.json();
            })
            .then(data => {
                if (data.length === 0) {
                    container.innerHTML = `<p class="text-center text-gray-500 py-4">Este vivero no tiene módulos activos o no se encontraron datos.</p>`;
                    return;
                }
                if (!container.querySelector('table')) {
                    buildTableShell(container, data);
                    setupInteractiveHighlighting(); 
                }
                updateTableContent(data);
            })
            .catch(error => {
                console.error('Error al cargar la tabla de monitoreo:', error);
                if (!container.querySelector('table')) {
                    container.innerHTML = `<p class="text-red-500 font-semibold text-center py-4">Error al cargar la tabla de monitoreo.</p>`;
                }
                 const lastUpdatedContainer = document.getElementById('last-updated-container');
                if(lastUpdatedContainer) {
                    lastUpdatedContainer.innerHTML = `<span class="text-red-500">Error al actualizar: ${new Date().toLocaleTimeString()}</span>`;
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('monitoreo-table-container')) {
                fetchAndRenderOwnerTable();
                monitoreoInterval = setInterval(fetchAndRenderOwnerTable, 30000);
            }
            setupHistoryModal();
        });
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.bomba-toggle-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const moduloId = this.dataset.moduloId;
                    const url = `/modulos/${moduloId}/toggle-bomba`;
                    this.disabled = true;
                    this.innerHTML = `<svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
                    fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                    })
                    .then(response => response.ok ? response.json() : Promise.reject('Error en respuesta'))
                    .then(data => {
                        this.disabled = false;
                        const isStateOne = data.bomba_estado;
                        this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9" /></svg>`;
                        this.setAttribute('title', isStateOne ? 'Encender Bomba' : 'Apagar Bomba');
                        this.classList.toggle('bg-green-200', isStateOne);
                        this.classList.toggle('text-green-800', isStateOne);
                        this.classList.toggle('hover:bg-green-300', isStateOne);
                        this.classList.toggle('bg-red-200', !isStateOne);
                        this.classList.toggle('text-red-800', !isStateOne);
                        this.classList.toggle('hover:bg-red-300', !isStateOne);
                        const statusCell = document.getElementById(`bomba-status-${moduloId}`);
                        if (statusCell) {
                           if (isStateOne) {
                                statusCell.innerHTML = `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-800"><span class="w-2 h-2 mr-2 bg-gray-500 rounded-full"></span> Apagada</span>`;
                            } else {
                                statusCell.innerHTML = `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-200 text-green-800"><span class="w-2 h-2 mr-2 bg-green-500 rounded-full"></span> Encendida</span>`;
                            }
                        }
                    })
                    .catch(error => {
                        this.disabled = false;
                        console.error('Error al cambiar estado de la bomba:', error);
                        this.innerHTML = `❓`;
                    });
                });
            });
        });
    </script>
</x-app-layout>