<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-hydro-text-light leading-tight">
            Detalle del Módulo: {{ $modulo->codigo_identificador }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="bg-hydro-card p-8 rounded-xl shadow-xl">
                <h3 class="text-xl font-bold text-white mb-6 border-b border-gray-700 pb-2">
                    Información General y Propietario
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-gray-300">
                    <div>
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Módulo</p>
                        <p class="text-lg font-bold text-white mb-2">{{ $modulo->codigo_identificador }}</p>
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider mt-4">Cultivo Actual</p>
                        <p class="text-lg font-bold text-green-400">{{ $modulo->cultivo_actual ?? 'N/A' }}</p>
                    </div>
                    <div>
                        @if ($modulo->vivero && $modulo->vivero->user)
                            <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Dueño (Cliente)</p>
                            <p class="text-lg font-bold text-white mb-2">{{ $modulo->vivero->user->full_name }}</p>
                            <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider mt-4">Email de Contacto</p>
                            <p class="text-lg text-blue-300">{{ $modulo->vivero->user->email }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Estado</p>
                        <p class="text-lg font-bold mb-2 @if($modulo->estado === 'Ocupado') text-yellow-400 @else text-green-400 @endif">{{ $modulo->estado }}</p>
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider mt-4">Fecha de Siembra</p>
                        <p class="text-lg">{{ $modulo->fecha_siembra ? \Carbon\Carbon::parse($modulo->fecha_siembra)->isoFormat('D MMM YYYY') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-hydro-card p-8 rounded-xl shadow-xl">
                <h3 class="text-xl font-bold text-white mb-6 border-b border-gray-700 pb-2">
                    Histórico de Parámetros (Actualizado en tiempo real)
                </h3>
                
                <div id="loading-charts" class="text-center text-gray-400 p-8">Cargando gráficos...</div>
                
                <div id="charts-container" class="grid grid-cols-1 lg:grid-cols-2 gap-8 hidden">
                    <div class="bg-gray-800 p-4 rounded-lg shadow-inner h-96"><canvas id="phChart"></canvas></div>
                    <div class="bg-gray-800 p-4 rounded-lg shadow-inner h-96"><canvas id="tempChart"></canvas></div>
                    <div class="bg-gray-800 p-4 rounded-lg shadow-inner h-96"><canvas id="ecChart"></canvas></div>
                    <div class="bg-gray-800 p-4 rounded-lg shadow-inner h-96"><canvas id="luzChart"></canvas></div>
                </div>
            </div>
            
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        const MODULO_ID = {{ $modulo->id }};
        const apiUrl = window.location.origin + `/api/dashboard/history/${MODULO_ID}`;
        
        // Variables para almacenar las instancias de los gráficos y poder actualizarlos
        let phChartInstance, tempChartInstance, ecChartInstance, luzChartInstance;

        function fetchAndRenderCharts() {
            fetch(apiUrl)
                .then(response => {
                    if (!response.ok) throw new Error('API Error: ' + response.statusText);
                    return response.json();
                })
                .then(data => {
                    document.getElementById('loading-charts').classList.add('hidden');
                    document.getElementById('charts-container').classList.remove('hidden');
                    
                    if (!data.labels || data.labels.length === 0) {
                        document.getElementById('charts-container').innerHTML = '<p class="text-center text-yellow-500 p-8 col-span-full">No hay lecturas históricas para mostrar.</p>';
                        return;
                    }

                    // Si los gráficos no existen, créalos. Si ya existen, actualiza sus datos.
                    updateOrCreateChart('phChart', phChartInstance, data.labels, { label: 'Nivel de pH', data: data.ph, borderColor: '#22c55e' });
                    updateOrCreateChart('tempChart', tempChartInstance, data.labels, { label: 'Temperatura (°C)', data: data.temperatura, borderColor: '#3b82f6' });
                    updateOrCreateChart('ecChart', ecChartInstance, data.labels, { label: 'Conductividad (EC)', data: data.ec, borderColor: '#f59e0b' });
                    updateOrCreateChart('luzChart', luzChartInstance, data.labels, { label: 'Luz (lux)', data: data.luz, borderColor: '#eab308' });
                })
                .catch(error => {
                    console.error('Error fetching history:', error);
                    document.getElementById('loading-charts').innerHTML = `<p class="text-red-500">Error al cargar datos históricos: ${error.message}</p>`;
                });
        }
        
        function updateOrCreateChart(canvasId, chartInstance, labels, dataset) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            // Si la instancia ya existe, solo actualizamos los datos para evitar parpadeos
            if (chartInstance) {
                chartInstance.data.labels = labels;
                chartInstance.data.datasets[0].data = dataset.data;
                chartInstance.update();
                return;
            }

            // Si no existe, creamos el gráfico por primera vez
            const newChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: dataset.label,
                        data: dataset.data,
                        borderColor: dataset.borderColor,
                        backgroundColor: `${dataset.borderColor}1A`, // Color con 10% de opacidad
                        tension: 0.3,
                        pointRadius: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        // CORRECCIÓN: Dejamos que el eje Y se ajuste automáticamente
                        y: { suggestedMin: dataset.min, suggestedMax: dataset.max, title: { display: false } },
                        x: { title: { display: false } }
                    },
                    plugins: { legend: { display: true, labels: { color: '#ccc' } } }
                }
            });

            // Guardamos la instancia para futuras actualizaciones
            if (canvasId === 'phChart') phChartInstance = newChartInstance;
            if (canvasId === 'tempChart') tempChartInstance = newChartInstance;
            if (canvasId === 'ecChart') ecChartInstance = newChartInstance;
            if (canvasId === 'luzChart') luzChartInstance = newChartInstance;
        }

        // Iniciar el proceso y luego refrescar cada 30 segundos
        document.addEventListener('DOMContentLoaded', () => {
            fetchAndRenderCharts(); // Carga inicial
            setInterval(fetchAndRenderCharts, 30000); // <-- CORRECCIÓN: Actualización automática
        });
    </script>
</x-app-layout>