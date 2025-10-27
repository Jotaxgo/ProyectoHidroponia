<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Dashboard del Administrador
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Sección de Estadísticas Generales (Existente) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Tarjetas de Estadísticas --}}
                <div class="bg-hydro-card p-6 rounded-lg shadow-xl">
                    <div class="flex items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Total de Usuarios</p>
                            <p class="text-3xl font-bold text-white">{{ $stats['total_users'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-hydro-card p-6 rounded-lg shadow-xl">
                    <div class="flex items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Total de Viveros</p>
                            <p class="text-3xl font-bold text-white">{{ $stats['total_viveros'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-hydro-card p-6 rounded-lg shadow-xl">
                    <div class="flex items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Total de Módulos</p>
                            <p class="text-3xl font-bold text-white">{{ $stats['total_modulos'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-hydro-card p-6 rounded-lg shadow-xl">
                    <div class="flex items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Módulos Ocupados</p>
                            <p class="text-3xl font-bold text-yellow-400">{{ $stats['modulos_ocupados'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN DE MONITOREO DE SENSORES (TABLA RESUMEN) -->
            <div class="bg-hydro-card p-6 rounded-lg shadow-xl overflow-x-auto">
                <h2 class="text-2xl font-bold text-white mb-4">Estado General de Módulos Ocupados</h2>
                <div id="monitoreo-table-container">
                    <!-- Tabla de datos se inyectará aquí -->
                    <p class="text-gray-400 animate-pulse">Cargando datos de módulos...</p>
                </div>
            </div>

        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- BLOQUE DE SCRIPTS PARA TABLA DINÁMICA DE MONITOREO --}}
    {{-- ========================================================= --}}
    <script>
        /**
         * Retorna las clases de estilo de Tailwind CSS basado en el estado de alerta.
         */
        function getAlertStyles(estado) {
            switch (estado) {
                case 'CRÍTICO':
                    return { text: 'text-white', bg: 'bg-red-600', badge: 'bg-red-700' };
                case 'ADVERTENCIA':
                    return { text: 'text-gray-900', bg: 'bg-yellow-400', badge: 'bg-yellow-500' };
                case 'OFFLINE':
                    return { text: 'text-gray-900', bg: 'bg-gray-400', badge: 'bg-gray-500' };
                case 'OK':
                default:
                    return { text: 'text-white', bg: 'bg-green-600', badge: 'bg-green-700' };
            }
        }

        /**
         * Genera el contenido de la tabla de monitoreo.
         */
        function fetchAndRenderTable() {
            const container = document.getElementById('monitoreo-table-container');
            const apiUrl = window.location.origin + '/api/dashboard/latest-data'; 
            
            container.innerHTML = `<p class="text-gray-400 animate-pulse">Refrescando datos...</p>`; 
            
            fetch(apiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error de conexión con la API: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.length === 0) {
                        container.innerHTML = `<p class="text-gray-400">No hay módulos ocupados con datos en la base de datos.</p>`;
                        return;
                    }

                    let tableHtml = `
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Módulo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Cultivo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">PH</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">EC</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Temp (°C)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Último Reporte</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                    `;

                    data.forEach(item => {
                        const styles = getAlertStyles(item.estado_alerta);
                        const isOffline = item.estado_alerta === 'OFFLINE';
                        
                        // Determinar el tiempo a mostrar: 
                        // Si está OFFLINE, usamos los minutos.
                        // Si está OK/Advertencia/Crítico, mostramos "Hace X min".
                        const tiempoReporte = (item.minutos_offline > 1 && item.minutos_offline !== null) 
                            ? `Hace ${item.minutos_offline} min`
                            : (item.minutos_offline === 0 ? 'Ahora mismo' : 'Hace poco');

                        const phDisplay = isOffline ? '---' : item.ph;
                        const ecDisplay = isOffline ? '---' : item.ec;
                        const tempDisplay = isOffline ? '---' : item.temperatura;
                        
                        tableHtml += `
                            <tr class="hover:bg-gray-800 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">${item.codigo}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">${item.cultivo || 'Sin asignar'}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">${phDisplay}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">${ecDisplay}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">${tempDisplay}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${styles.bg} ${styles.text}">
                                        ${item.estado_alerta}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    ${isOffline ? `Desconectado (${tiempoReporte})` : tiempoReporte}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="${window.location.origin}/admin/modulos/${item.modulo_id}/detail" class="text-indigo-400 hover:text-indigo-300 transition duration-150">Ver Detalle</a>
                                </td>
                            </tr>
                        `;
                    });

                    tableHtml += `
                            </tbody>
                        </table>
                    `;
                    container.innerHTML = tableHtml;

                })
                .catch(error => {
                    console.error('Error en la obtención de datos:', error);
                    container.innerHTML = `<p class="text-red-400">Error al cargar la tabla: ${error.message}.</p>`;
                });
        }

        // Inicializar el proceso de refresco automático
        document.addEventListener('DOMContentLoaded', () => {
            fetchAndRenderTable(); // Carga inicial
            setInterval(fetchAndRenderTable, 30000); // Recarga cada 30 segundos
        });
    </script>
</x-app-layout>
