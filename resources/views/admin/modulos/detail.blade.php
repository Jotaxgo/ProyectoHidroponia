<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl leading-tight">
            🔧 Detalle del Módulo: <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">{{ $modulo->codigo_identificador }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- 1. PANEL DE INFORMACIÓN CLAVE -->
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg p-8 rounded-2xl shadow-lg" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                <h3 class="text-xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent mb-6 border-b border-[#e0e0e0] pb-2">
                    📋 Información General y Propietario
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-[#555555]">
                    <!-- Columna 1: Módulo y Cultivo -->
                    <div>
                        <p class="text-sm font-semibold text-[#999999] uppercase tracking-wider">🔧 Módulo</p>
                        <p class="text-lg font-bold text-[#1a1a1a] mb-2">{{ $modulo->codigo_identificador }}</p>
                        <p class="text-sm font-semibold text-[#999999] uppercase tracking-wider mt-4">🌱 Cultivo Actual</p>
                        <p class="text-lg font-bold text-[#96d900]">{{ $modulo->cultivo_actual ?? 'N/A' }}</p>
                    </div>
                    <!-- Columna 2: Propietario -->
                    <div>
                        @if ($modulo->vivero && $modulo->vivero->user)
                            <p class="text-sm font-semibold text-[#999999] uppercase tracking-wider">👤 Dueño (Cliente)</p>
                            <p class="text-lg font-bold text-[#1a1a1a] mb-2">{{ $modulo->vivero->user->full_name }}</p>
                            <p class="text-sm font-semibold text-[#999999] uppercase tracking-wider mt-4">📧 Email de Contacto</p>
                            <p class="text-lg text-[#ff4b65]">{{ $modulo->vivero->user->email }}</p>
                        @endif
                    </div>
                    <!-- Columna 3: Estado y Fechas -->
                    <div>
                        <p class="text-sm font-semibold text-[#999999] uppercase tracking-wider">⚙️ Estado</p>
                        <p class="text-lg font-bold mb-2 inline-flex items-center px-3 py-1 rounded-full @if($modulo->estado === 'Ocupado') bg-amber-400/20 text-amber-700 @elseif($modulo->estado === 'Disponible') bg-[#96d900]/20 text-[#6b9b00] @else bg-red-500/20 text-red-600 @endif">
                            @if($modulo->estado === 'Ocupado')🌱 @elseif($modulo->estado === 'Disponible')✅ @else🔧 @endif {{ $modulo->estado }}
                        </p>
                        <p class="text-sm font-semibold text-[#999999] uppercase tracking-wider mt-4">📅 Fecha de Siembra</p>
                        <p class="text-lg text-[#1a1a1a]">{{ $modulo->fecha_siembra ? \Carbon\Carbon::parse($modulo->fecha_siembra)->isoFormat('D MMM YYYY') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- 2. SECCIÓN DE GRÁFICOS -->
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg p-8 rounded-2xl shadow-lg" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                <h3 class="text-xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent mb-6 border-b border-[#e0e0e0] pb-2">
                    📊 Histórico de Parámetros (Actualizado en tiempo real)
                </h3>
                
                <div id="loading-charts" class="text-center text-[#999999] p-8">⏳ Cargando gráficos...</div>
                
                {{-- 
                    ======================================================
                    MODIFICACIÓN: Cuadrícula de 2 a 3 columnas (lg:grid-cols-3)
                    ======================================================
                --}}
                <div id="charts-container" class="grid grid-cols-1 lg:grid-cols-3 gap-8 hidden">
                    {{-- Gráfico 1: pH --}}
                    <div class="bg-[#fafafa] p-4 rounded-lg shadow-inner h-96 border border-[#e0e0e0]">
                        <h4 class="text-lg font-semibold text-white mb-3">Gráfico de pH del Agua</h4>
                        <canvas id="phChart"></canvas>
                    </div>
                    {{-- Gráfico 2: Temperatura --}}
                    <div class="bg-gray-800 p-4 rounded-lg shadow-inner h-96">
                        <h4 class="text-lg font-semibold text-white mb-3">Gráfico de Temperatura (°C)</h4>
                        <canvas id="tempChart"></canvas>
                    </div>
                    {{-- Gráfico 3: EC --}}
                    <div class="bg-gray-800 p-4 rounded-lg shadow-inner h-96">
                        <h4 class="text-lg font-semibold text-white mb-3">Gráfico de Conductividad (EC)</h4>
                        <canvas id="ecChart"></canvas>
                    </div>
                    {{-- Gráfico 4: Luz --}}
                    <div class="bg-gray-800 p-4 rounded-lg shadow-inner h-96">
                        <h4 class="text-lg font-semibold text-white mb-3">Gráfico de Luz (lux)</h4>
                        <canvas id="luzChart"></canvas>
                    </div>
                    
                    {{-- 
                        ======================================================
                        NUEVO GRÁFICO: Humedad
                        ======================================================
                    --}}
                    <div class="bg-gray-800 p-4 rounded-lg shadow-inner h-96">
                        <h4 class="text-lg font-semibold text-white mb-3">Gráfico de Humedad (%)</h4>
                        <canvas id="humedadChart"></canvas>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- LIBRERÍA DE GRÁFICOS (Chart.js) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    {{-- ====================================================== --}}
    {{-- SCRIPT PARA GRÁFICOS (ACTUALIZADO PARA HUMEDAD) --}}
    {{-- ====================================================== --}}
    <script>
        const MODULO_ID = {{ $modulo->id }};
        const apiUrl = window.location.origin + `/admin/dashboard/history/${MODULO_ID}`;

        // MODIFICACIÓN: Añadir instancia para el gráfico de humedad
        let phChartInstance, tempChartInstance, ecChartInstance, luzChartInstance, humedadChartInstance;
        let chartsInterval; 

        function fetchAndRenderCharts() {
            fetch(apiUrl, {
                headers: { 
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (response.status === 419 || response.status === 401) {
                        clearInterval(chartsInterval); 
                        document.getElementById('loading-charts').innerHTML = `<p class="text-yellow-400 font-bold">Tu sesión ha expirado. Redirigiendo...</p>`;
                        window.location.reload(); 
                        throw new Error('Sesión expirada.'); 
                    }
                    if (!response.ok) throw new Error('API Error: ' + response.statusText);
                    return response.json();
                })
                .then(data => {
                    document.getElementById('loading-charts').classList.add('hidden');
                    document.getElementById('charts-container').classList.remove('hidden');
                    
                    if (data.labels && data.labels.length > 0) {
                        renderAllCharts(data);
                    } else {
                        document.getElementById('charts-container').innerHTML = '<p class="text-center text-yellow-500 p-8 col-span-full">No hay suficientes lecturas históricas para mostrar los gráficos.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching history:', error);
                    if (error.message !== 'Sesión expirada.') {
                        document.getElementById('loading-charts').innerHTML = `<p class="text-red-500">Error al cargar datos históricos: ${error.message}</p>`;
                        document.getElementById('loading-charts').classList.remove('hidden'); 
                    }
                });
        }
        
        function updateOrCreateChart(canvasId, chartInstance, labels, dataset) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            if (chartInstance) {
                chartInstance.data.labels = labels;
                chartInstance.data.datasets[0].data = dataset.data;
                chartInstance.update();
                return chartInstance; 
            }

            const newChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: dataset.label,
                        data: dataset.data,
                        borderColor: dataset.borderColor,
                        backgroundColor: `${dataset.borderColor}1A`,
                        tension: 0.3,
                        pointRadius: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { 
                            title: { display: false },
                            // Quitamos min/max para que se autoajuste
                        }, 
                        x: { title: { display: false } }
                    },
                    plugins: { legend: { display: true, labels: { color: '#ccc' } } }
                }
            });
            
            // MODIFICACIÓN: Guardar la instancia del nuevo gráfico
            if (canvasId === 'phChart') phChartInstance = newChartInstance;
            if (canvasId === 'tempChart') tempChartInstance = newChartInstance;
            if (canvasId === 'ecChart') ecChartInstance = newChartInstance;
            if (canvasId === 'luzChart') luzChartInstance = newChartInstance;
            if (canvasId === 'humedadChart') humedadChartInstance = newChartInstance; // <-- AÑADIDO

            return newChartInstance;
        }

        // Función que actualiza todas las instancias
        function renderAllCharts(data) {
            Chart.defaults.color = '#ccc';
            Chart.defaults.font.family = 'Inter';

            phChartInstance = updateOrCreateChart('phChart', phChartInstance, data.labels, { label: 'Nivel de pH', data: data.ph, borderColor: '#22c55e' });
            tempChartInstance = updateOrCreateChart('tempChart', tempChartInstance, data.labels, { label: 'Temperatura (°C)', data: data.temperatura, borderColor: '#3b82f6' });
            ecChartInstance = updateOrCreateChart('ecChart', ecChartInstance, data.labels, { label: 'Conductividad (EC)', data: data.ec, borderColor: '#f59e0b' });
            luzChartInstance = updateOrCreateChart('luzChart', luzChartInstance, data.labels, { label: 'Luz (lux)', data: data.luz, borderColor: '#eab308' });
            
            // --- MODIFICACIÓN: AÑADIR LLAMADA AL GRÁFICO DE HUMEDAD ---
            // (Usamos un color nuevo, ej: sky blue)
            humedadChartInstance = updateOrCreateChart('humedadChart', humedadChartInstance, data.labels, { label: 'Humedad (%)', data: data.humedad, borderColor: '#0ea5e9' });
        }

        // Iniciar el proceso y luego refrescar cada 30 segundos
        document.addEventListener('DOMContentLoaded', () => {
            fetchAndRenderCharts(); // Carga inicial
            chartsInterval = setInterval(fetchAndRenderCharts, 30000); // Refresco automático
        });
    </script>
</x-app-layout>