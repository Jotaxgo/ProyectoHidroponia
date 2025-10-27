<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-hydro-text-light leading-tight">
            Detalle del Módulo: {{ $modulo->codigo_identificador }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- 1. PANEL DE INFORMACIÓN CLAVE -->
            <div class="bg-hydro-card p-8 rounded-xl shadow-xl">
                <h3 class="text-xl font-bold text-white mb-6 border-b border-gray-700 pb-2">
                    Información General y Propietario
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-gray-300">
                    <!-- Columna 1: Módulo y Cultivo -->
                    <div>
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Módulo</p>
                        <p class="text-lg font-bold text-white mb-2">{{ $modulo->codigo_identificador }}</p>

                        
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider mt-4">Cultivo Actual</p>
                        <p class="text-lg font-bold text-green-400">{{ $modulo->cultivo_actual ?? 'N/A' }}</p>
                    </div>

                    <!-- Columna 2: Propietario (Dueño del Vivero) -->
                    <div>
                        @if ($modulo->vivero && $modulo->vivero->user)
                            <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Dueño (Cliente)</p>
                            <p class="text-lg font-bold text-white mb-2">{{ $modulo->vivero->user->full_name }}</p>
                            
                            <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider mt-4">Email de Contacto</p>
                            <p class="text-lg text-blue-300">{{ $modulo->vivero->user->email }}</p>
                        @else
                            <p class="text-lg text-yellow-500 font-bold">Sin Dueño Asignado</p>
                        @endif
                    </div>
                    
                    <!-- Columna 3: Estado y Fechas -->
                    <div>
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Estado</p>
                        <p class="text-lg font-bold mb-2 
                            @if($modulo->estado === 'Ocupado') text-yellow-400 
                            @elseif($modulo->estado === 'Disponible') text-green-400 
                            @else text-red-400 
                            @endif">
                            {{ $modulo->estado }}
                        </p>
                        
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider mt-4">Fecha de Siembra</p>
                        <p class="text-lg">{{ $modulo->fecha_siembra ? \Carbon\Carbon::parse($modulo->fecha_siembra)->isoFormat('D MMM YYYY') : 'N/A' }}</p>

                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider mt-4">Capacidad</p>
                        <p class="text-lg">{{ $modulo->capacidad }} plantas</p>
                    </div>
                </div>
            </div>

            <!-- 2. SECCIÓN DE GRÁFICOS (Aquí implementaremos el código de gráficos) -->
            <div class="bg-hydro-card p-8 rounded-xl shadow-xl">
                <h3 class="text-xl font-bold text-white mb-6 border-b border-gray-700 pb-2">
                    Histórico de Parámetros (Últimas 24 Horas)
                </h3>
                
                <div id="loading-charts" class="text-center text-gray-400 p-8">
                    Cargando datos históricos...
                </div>
                
                <!-- Contenedores para los gráficos -->
                <div id="charts-container" class="grid grid-cols-1 lg:grid-cols-2 gap-8 hidden">
                    <div class="bg-gray-800 p-4 rounded-lg shadow-inner">
                        <h4 class="text-lg font-semibold text-white mb-3">Gráfico de pH del Agua</h4>
                        <canvas id="phChart"></canvas>
                    </div>
                    <div class="bg-gray-800 p-4 rounded-lg shadow-inner">
                        <h4 class="text-lg font-semibold text-white mb-3">Gráfico de Temperatura del Agua (°C)</h4>
                        <canvas id="tempChart"></canvas>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- LIBRERÍA DE GRÁFICOS (Chart.js) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        // ID del módulo para la API
        const MODULO_ID = {{ $modulo->id }};
        const apiUrl = window.location.origin + `/api/dashboard/history/${MODULO_ID}`;

        document.addEventListener('DOMContentLoaded', function () {
            fetch(apiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('API Error: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('loading-charts').classList.add('hidden');
                    document.getElementById('charts-container').classList.remove('hidden');
                    
                    if (data.labels.length > 0) {
                        renderCharts(data);
                    } else {
                        document.getElementById('charts-container').innerHTML = '<p class="text-center text-yellow-500 p-8">No hay suficientes lecturas históricas para mostrar los gráficos.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching history:', error);
                    document.getElementById('loading-charts').innerHTML = `<p class="text-red-500">Error al cargar datos históricos: ${error.message}</p>`;
                });
        });

        function renderCharts(data) {
            Chart.defaults.color = '#ccc'; // Color general para el texto del gráfico
            Chart.defaults.font.family = 'Inter';

            // --- Gráfico de PH ---
            new Chart(document.getElementById('phChart'), {
                type: 'line',
                data: {
                    labels: data.labels, // Horas o fechas
                    datasets: [{
                        label: 'Nivel de pH',
                        data: data.ph,
                        borderColor: '#22c55e', // Color verde
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.3,
                        pointRadius: 2,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            min: 5.0,
                            max: 7.0,
                            title: { display: true, text: 'pH' }
                        },
                        x: {
                            title: { display: true, text: 'Hora del Día' }
                        }
                    },
                    plugins: {
                        legend: { display: true }
                    }
                }
            });

            // --- Gráfico de Temperatura ---
            new Chart(document.getElementById('tempChart'), {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Temperatura del Agua (°C)',
                        data: data.temperatura,
                        borderColor: '#3b82f6', // Color azul
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.3,
                        pointRadius: 2,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            min: 18,
                            max: 30,
                            title: { display: true, text: 'Temperatura (°C)' }
                        },
                        x: {
                            title: { display: true, text: 'Hora del Día' }
                        }
                    },
                    plugins: {
                        legend: { display: true }
                    }
                }
            });
        }
    </script>
</x-app-layout>
