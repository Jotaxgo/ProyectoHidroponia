@php
function getStatusColor($value, $min, $max) {
    $value = (float)$value;
    $min = (float)$min;
    $max = (float)$max;
    $warningThreshold = ($max - $min) * 0.1; // 10% de margen para advertencia

    if ($value < $min - $warningThreshold || $value > $max + $warningThreshold) {
        return 'red'; // Crítico
    }
    if ($value < $min || $value > $max) {
        return 'amber'; // Advertencia
    }
    return 'green'; // OK
}
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl leading-tight">
            🔧 Detalle del Módulo: <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">{{ $modulo->codigo_identificador }}</span>
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ tab: 'status', range: '24h' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- SELECTOR DE RANGO DE TIEMPO GLOBAL --}}
            <div class="flex justify-end mb-4">
                <div class="flex space-x-2">
                    <span class="text-sm font-semibold text-gray-500 self-center mr-2">Rango:</span>
                    <button @click="range = '6h'; fetchAndRenderCharts(range)" :class="{ 'bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white shadow-md': range === '6h', 'bg-white text-gray-600 hover:bg-gray-100': range !== '6h' }" class="px-4 py-2 text-sm font-semibold rounded-lg shadow-sm border border-gray-200 transition-colors">6h</button>
                    <button @click="range = '12h'; fetchAndRenderCharts(range)" :class="{ 'bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white shadow-md': range === '12h', 'bg-white text-gray-600 hover:bg-gray-100': range !== '12h' }" class="px-4 py-2 text-sm font-semibold rounded-lg shadow-sm border border-gray-200 transition-colors">12h</button>
                    <button @click="range = '24h'; fetchAndRenderCharts(range)" :class="{ 'bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white shadow-md': range === '24h', 'bg-white text-gray-600 hover:bg-gray-100': range !== '24h' }" class="px-4 py-2 text-sm font-semibold rounded-lg shadow-sm border border-gray-200 transition-colors">24h</button>
                </div>
            </div>

            {{-- PESTAÑAS DE NAVEGACIÓN --}}
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg p-2 rounded-2xl shadow-lg" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                <div class="flex space-x-2">
                    <button @click="tab = 'status'" :class="{ 'bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white shadow-md': tab === 'status', 'text-[#555555] hover:bg-gray-200/50': tab !== 'status' }" class="w-full text-center font-semibold py-3 px-4 rounded-xl transition-all duration-300">
                        Estado Actual
                    </button>
                    <button @click="tab = 'table'" :class="{ 'bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white shadow-md': tab === 'table', 'text-[#555555] hover:bg-gray-200/50': tab !== 'table' }" class="w-full text-center font-semibold py-3 px-4 rounded-xl transition-all duration-300">
                        Tabla Histórico
                    </button>
                    <button @click="tab = 'graphs'" :class="{ 'bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white shadow-md': tab === 'graphs', 'text-[#555555] hover:bg-gray-200/50': tab !== 'graphs' }" class="w-full text-center font-semibold py-3 px-4 rounded-xl transition-all duration-300">
                        Gráficos Históricos
                    </button>
                </div>
            </div>

            {{-- VISTA: ESTADO ACTUAL --}}
            <div x-show="tab === 'status'" x-transition>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Columna de Información General --}}
                    <div class="lg:col-span-1 space-y-8">
                        <div class="bg-white/90 backdrop-filter backdrop-blur-lg p-6 rounded-2xl shadow-lg" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                            <h3 class="text-lg font-bold text-[#1a1a1a] mb-4">📋 Información</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-[#555555]">Cultivo:</span>
                                    <span class="font-semibold text-[#1a1a1a]">{{ $modulo->cultivo_actual ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[#555555]">Siembra:</span>
                                    <span class="font-semibold text-[#1a1a1a]">{{ $modulo->fecha_siembra ? \Carbon\Carbon::parse($modulo->fecha_siembra)->isoFormat('D MMM YYYY') : 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[#555555]">Estado:</span>
                                    <span class="font-semibold px-2 py-0.5 rounded-full text-xs @if($modulo->estado === 'Ocupado') bg-amber-400/20 text-amber-700 @elseif($modulo->estado === 'Disponible') bg-green-500/20 text-green-700 @else bg-red-500/20 text-red-600 @endif">{{ $modulo->estado }}</span>
                                </div>
                                @if ($modulo->vivero && $modulo->vivero->user)
                                <div class="pt-3 border-t border-gray-200">
                                    <div class="flex justify-between">
                                        <span class="text-[#555555]">Dueño:</span>
                                        <span class="font-semibold text-[#1a1a1a]">{{ $modulo->vivero->user->full_name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-[#555555]">Email:</span>
                                        <span class="font-semibold text-[#ff4b65]">{{ $modulo->vivero->user->email }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Columna de Medidores de Estado --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white/90 backdrop-filter backdrop-blur-lg p-6 rounded-2xl shadow-lg" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                            <h3 class="text-lg font-bold text-[#1a1a1a] mb-4">🌡️ Parámetros Actuales</h3>
                            @if($latestLectura)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach(['ph' => 'pH', 'temperatura' => '°C', 'ec' => 'EC', 'humedad' => '%', 'luz' => 'lux'] as $key => $unit)
                                        @php
                                            $value = (float)$latestLectura->$key;
                                            $hasLimits = isset($limits[$key]['min']) && isset($limits[$key]['max']);
                                            
                                            $statusColor = 'neutral';
                                            if ($hasLimits) {
                                                $statusColor = getStatusColor($value, $limits[$key]['min'], $limits[$key]['max']);
                                            }

                                            $colorClasses = [
                                                'red' => ['text' => 'text-red-600', 'bg' => 'bg-red-500/20', 'border' => 'border-red-500'],
                                                'amber' => ['text' => 'text-amber-600', 'bg' => 'bg-amber-500/20', 'border' => 'border-amber-500'],
                                                'green' => ['text' => 'text-green-600', 'bg' => 'bg-green-500/20', 'border' => 'border-green-500'],
                                                'neutral' => ['text' => 'text-gray-600', 'bg' => 'bg-gray-100', 'border' => 'border-gray-200'],
                                            ][$statusColor];
                                        @endphp
                                        <div class="p-4 rounded-xl {{ $colorClasses['bg'] }} border {{ $colorClasses['border'] }}">
                                            <div class="flex items-center justify-between">
                                                <span class="font-bold {{ $colorClasses['text'] }}">{{ strtoupper($key) }}</span>
                                                <div class="text-right">
                                                    <p class="text-2xl font-bold {{ $colorClasses['text'] }}">{{ number_format($value, ($key === 'ph' || $key === 'ec' || $key === 'temperatura' || $key === 'humedad') ? 2 : 0) }} <span class="text-lg">{{ $unit }}</span></p>
                                                    @if($hasLimits)
                                                        <p class="text-xs font-semibold {{ $colorClasses['text'] }} opacity-80">Ideal: {{ $limits[$key]['min'] }} - {{ $limits[$key]['max'] }}</p>
                                                    @else
                                                        <p class="text-xs font-semibold text-gray-500 opacity-80">Sin rango ideal definido</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-400 mt-4 text-right">Última lectura: {{ $latestLectura->created_at->diffForHumans() }}</p>
                            @else
                                <p class="text-center text-gray-500 py-8">No hay lecturas recientes para este módulo.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- VISTA: TABLA HISTÓRICO --}}
            <div x-show="tab === 'table'" x-cloak x-transition>
                <div class="bg-white/90 backdrop-filter backdrop-blur-lg p-8 rounded-2xl shadow-lg" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                    <h3 class="text-xl font-bold text-[#1a1a1a] mb-6">📖 Historial de Lecturas</h3>
                    <div id="history-table-container">
                        <p class="text-[#999999] animate-pulse">Cargando tabla de historial...</p>
                    </div>
                </div>
            </div>

            {{-- VISTA: GRÁFICOS HISTÓRICOS --}}
            <div x-show="tab === 'graphs'" x-cloak x-transition>
                <div class="bg-white/90 backdrop-filter backdrop-blur-lg p-8 rounded-2xl shadow-lg" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                    <h3 class="text-xl font-bold text-[#1a1a1a] mb-6">📊 Gráfico de Parámetros</h3>
                    <div id="loading-charts" class="text-center text-gray-500 p-8">⏳ Cargando gráficos...</div>
                    <div id="charts-container" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8 hidden">
                        @foreach(['ph', 'temperatura', 'ec', 'luz', 'humedad'] as $chartKey)
                        <div class="bg-gray-50 p-4 rounded-xl h-80 border border-gray-200">
                            <canvas id="{{ $chartKey }}Chart"></canvas>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

    <script>
        const MODULO_ID = {{ $modulo->id }};
        const apiUrl = `${window.location.origin}/admin/dashboard/history/${MODULO_ID}`;
        let chartInstances = {};

        function renderHistoryTable(data) {
            const container = document.getElementById('history-table-container');
            if (!container) return;

            if (!data.labels || data.labels.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-500 py-4">No hay lecturas recientes para mostrar en la tabla.</p>';
                return;
            }

            const rows = data.labels.map((label, index) => ({
                timestamp: label,
                ph: data.ph[index],
                temperatura: data.temperatura[index],
                ec: data.ec[index],
                luz: data.luz[index],
                humedad: data.humedad[index]
            })).reverse();

            let tableHtml = `
                <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                    <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-semibold text-gray-700">Fecha y Hora</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-semibold text-gray-700">pH</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-semibold text-gray-700">Temp (°C)</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-semibold text-gray-700">EC</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-semibold text-gray-700">Luz (lux)</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-semibold text-gray-700">Humedad (%)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
            `;

            rows.forEach(row => {
                tableHtml += `
                    <tr>
                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">${new Date(row.timestamp.replace(' ', 'T')).toLocaleString('es-ES', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' })}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">${row.ph !== null ? row.ph.toFixed(2) : '---'}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">${row.temperatura !== null ? row.temperatura.toFixed(1) : '---'}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">${row.ec !== null ? row.ec.toFixed(2) : '---'}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">${row.luz !== null ? Math.round(row.luz) : '---'}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">${row.humedad !== null ? row.humedad.toFixed(1) : '---'}</td>
                    </tr>
                `;
            });

            tableHtml += `</tbody></table></div>`;
            container.innerHTML = tableHtml;
        }

        function createOrUpdateChart(key, labels, dataset) {
            const ctx = document.getElementById(`${key}Chart`).getContext('2d');
            if (chartInstances[key]) {
                chartInstances[key].data.labels = labels;
                chartInstances[key].data.datasets[0].data = dataset.data;
                chartInstances[key].update('none');
            } else {
                const colors = {
                    ph: '#ff4b65',
                    temperatura: '#3b82f6',
                    ec: '#f59e0b',
                    luz: '#eab308',
                    humedad: '#0ea5e9'
                };
                const borderColor = colors[key] || '#9ca3af';

                chartInstances[key] = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: dataset.label,
                            data: dataset.data,
                            borderColor: borderColor,
                            backgroundColor: `${borderColor}33`,
                            tension: 0.4,
                            pointRadius: 2,
                            pointHoverRadius: 6,
                            fill: true,
                            pointHitRadius: 20,
                            spanGaps: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                border: { display: false },
                                grid: { color: '#e5e7eb' },
                                ticks: { color: '#6b7280', font: { weight: '600' } }
                            },
                            x: {
                                type: 'time',
                                time: { unit: 'hour', displayFormats: { hour: 'HH:mm' } },
                                grid: { display: false },
                                ticks: { color: '#6b7280', font: { weight: '600' } }
                            }
                        },
                        plugins: {
                            legend: { display: true, position: 'top', align: 'end', labels: { color: '#374151', font: { weight: 'bold' } } }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        }
                    }
                });
            }
        }

        function fetchAndRenderCharts(range = '24h') {
            const urlWithRange = `${apiUrl}?range=${range}`;

            document.getElementById('history-table-container').innerHTML = '<p class="text-[#999999] animate-pulse text-center py-4">Cargando historial...</p>';
            document.getElementById('loading-charts').classList.remove('hidden');
            document.getElementById('charts-container').classList.add('hidden');

            fetch(urlWithRange, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }})
            .then(response => {
                if (response.status === 419) { window.location.reload(); }
                if (!response.ok) throw new Error('API Error');
                return response.json();
            })
            .then(data => {
                renderHistoryTable(data);

                document.getElementById('loading-charts').classList.add('hidden');
                document.getElementById('charts-container').classList.remove('hidden');
                
                if (data.labels && data.labels.length > 0) {
                    const chartData = {
                        ph: { label: 'Nivel de pH', data: data.ph },
                        temperatura: { label: 'Temperatura (°C)', data: data.temperatura },
                        ec: { label: 'Conductividad (EC)', data: data.ec },
                        luz: { label: 'Luz (lux)', data: data.luz },
                        humedad: { label: 'Humedad (%)', data: data.humedad }
                    };
                    for (const key in chartData) {
                        createOrUpdateChart(key, data.labels, chartData[key]);
                    }
                } else {
                    document.getElementById('charts-container').innerHTML = '<p class="text-center text-gray-500 py-8 col-span-full">No hay suficientes lecturas históricas para mostrar los gráficos.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching history:', error);
                document.getElementById('history-table-container').innerHTML = '<p class="text-red-500 text-center py-4">Error al cargar el historial.</p>';
                document.getElementById('loading-charts').innerHTML = `<p class="text-red-500">Error al cargar datos históricos.</p>`;
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchAndRenderCharts('24h');
        });
    </script>
</x-app-layout>