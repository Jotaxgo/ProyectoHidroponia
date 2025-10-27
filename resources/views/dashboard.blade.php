{{-- resources/views/dashboard.blade.php (SCRIPT CORREGIDO FINAL) --}}
<x-app-layout>
    {{-- ... (Tu slot de header y sección de estadísticas - sin cambios) ... --}}
     <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Dashboard del Administrador
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Sección de Estadísticas Generales (Existente) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                 {{-- Tarjeta Total Usuarios --}}
                <div class="bg-hydro-card p-6 rounded-lg shadow-xl">
                    <div>
                        <p class="text-sm font-medium text-gray-400">Total de Usuarios</p>
                        <p class="text-3xl font-bold text-white">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
                {{-- Tarjeta Total Viveros --}}
                <div class="bg-hydro-card p-6 rounded-lg shadow-xl">
                     <div>
                        <p class="text-sm font-medium text-gray-400">Total de Viveros</p>
                        <p class="text-3xl font-bold text-white">{{ $stats['total_viveros'] }}</p>
                    </div>
                </div>
                 {{-- Tarjeta Total Módulos --}}
                <div class="bg-hydro-card p-6 rounded-lg shadow-xl">
                    <div>
                        <p class="text-sm font-medium text-gray-400">Total de Módulos</p>
                        <p class="text-3xl font-bold text-white">{{ $stats['total_modulos'] }}</p>
                    </div>
                </div>
                 {{-- Tarjeta Módulos Ocupados --}}
                <div class="bg-hydro-card p-6 rounded-lg shadow-xl">
                     <div>
                        <p class="text-sm font-medium text-gray-400">Módulos Ocupados</p>
                        <p class="text-3xl font-bold text-yellow-400">{{ $stats['modulos_ocupados'] }}</p>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN DE MONITOREO DE SENSORES (TABLA RESUMEN) --}}
            <div class="bg-hydro-card p-6 rounded-lg shadow-xl overflow-x-auto">
                <h2 class="text-2xl font-bold text-white mb-4">Estado General de Módulos Ocupados</h2>
                <div id="monitoreo-table-container">
                    {{-- Tabla de datos se inyectará aquí --}}
                    <p class="text-gray-400 animate-pulse">Cargando datos de módulos...</p>
                </div>
            </div>

        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- BLOQUE DE SCRIPTS PARA TABLA DINÁMICA DE MONITOREO (CORREGIDO) --}}
    {{-- ========================================================= --}}
    <script>
        /**
         * Retorna las clases de estilo de Tailwind CSS basado en el estado de alerta.
         */
        function getAlertStyles(estado) {
            estado = estado || 'Sin Lecturas';
            switch (estado) {
                case 'CRÍTICO': return { text: 'text-white', bg: 'bg-red-600 font-semibold' }; // Añadido font-semibold
                case 'ADVERTENCIA': return { text: 'text-yellow-900', bg: 'bg-yellow-400 font-semibold' }; // Ajustado color texto
                case 'OFFLINE': return { text: 'text-gray-100', bg: 'bg-gray-600 font-semibold' }; // Ajustado color texto y fondo
                case 'Sin Lecturas': return { text: 'text-gray-100', bg: 'bg-gray-600 font-semibold' }; // Igual que Offline
                case 'OK': default: return { text: 'text-white', bg: 'bg-green-600 font-semibold' };
            }
        }

        /**
         * Intenta convertir un valor a número y formatearlo con toFixed.
         * Devuelve '---' si no es un número válido o es null/undefined.
         */
        function formatNumber(value, decimals) {
            if (value === null || typeof value === 'undefined') return '---'; // Manejo explícito de null/undefined
            const numberValue = parseFloat(value);
            if (!isNaN(numberValue)) {
                return numberValue.toFixed(decimals);
            }
            return '---'; // Si no se puede convertir
        }

        /**
         * Formatea los minutos desde la última lectura en un texto legible.
         */
        function formatTimeAgo(minutes, lastHour) {
             if (minutes === null || typeof minutes === 'undefined') {
                 // Si minutes es null, podría ser 'Sin Lecturas'
                 return 'Nunca';
             }
             if (minutes < 1) {
                 return 'Hace instantes';
             }
             if (minutes < 60) {
                 return `Hace ${minutes} min`;
             }
              if (minutes < 120) { // Menos de 2 horas
                 return 'Hace 1 hora';
             }
             if (minutes < 1440) { // Menos de 24 horas
                  return `Hace ${Math.floor(minutes / 60)} horas`;
             }
             // Si pasaron más de 24h, mostramos la última hora registrada si existe
             if(lastHour) {
                 return `Ayer a las ${lastHour}`; // O un formato de fecha más completo
             }
             return 'Hace mucho'; // Fallback
        }


        /**
         * Genera el contenido de la tabla de monitoreo.
         */
        function fetchAndRenderAdminTable() {
        const container = document.getElementById('monitoreo-table-container');
        const apiUrl = window.location.origin + '/api/dashboard/latest-data';

        if (container.querySelector('table')) {
            container.querySelector('tbody').classList.add('opacity-50', 'transition-opacity');
        } else {
            container.innerHTML = `<p class="text-gray-400 animate-pulse">Refrescando datos...</p>`;
        }

        fetch(apiUrl, { headers: { 'Accept': 'application/json' } })
        .then(response => {
            if (!response.ok) throw new Error('Error API: ' + response.statusText);
            return response.json();
        })
        .then(data => {
            if (!Array.isArray(data)) throw new Error('Respuesta API inválida.');
            if (data.length === 0) {
                container.innerHTML = `<p class="text-gray-400">No hay módulos ocupados con datos.</p>`;
                return;
            }

            let tableHtml = `
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700/50">
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
                const codigoModulo = item.nombre_modulo || `ID:${item.modulo_id}`;
                // Mostrar "Sin asignar" si cultivo_actual es null o vacío
                const cultivoActual = item.cultivo_actual || '<span class="italic text-gray-500">Sin asignar</span>'; 
                const estadoAlerta = item.estado_alerta || 'Sin Lecturas'; 
                const styles = getAlertStyles(estadoAlerta);
                const isOfflineOrNoData = estadoAlerta === 'OFFLINE' || estadoAlerta === 'Sin Lecturas';

                // --- CORRECCIÓN TIEMPO: Usar 'minutos_offline' ---
                const tiempoReporte = formatTimeAgo(item.minutos_offline, item.hora_ultima_lectura);

                const phDisplay = formatNumber(item.ph, 2);
                const ecDisplay = formatNumber(item.ec, 2);
                const tempDisplay = formatNumber(item.temperatura, 1);

                tableHtml += `
                    <tr class="hover:bg-gray-800 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">${codigoModulo}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">${cultivoActual}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">${phDisplay}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">${ecDisplay}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">${tempDisplay}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 rounded-full ${styles.bg} ${styles.text}">
                                ${estadoAlerta}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">${tiempoReporte}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-400 hover:text-blue-300">
                            <a href="${window.location.origin}/admin/modulos/${item.modulo_id}/detail">Ver Detalle</a>
                        </td>
                    </tr>
                `;
            });

            tableHtml += `</tbody></table>`;
            container.innerHTML = tableHtml;

        })
        .catch(error => {
            console.error('Error en la obtención de datos:', error);
            container.innerHTML = `<p class="text-red-400">Error al cargar la tabla: ${error.message}. Revisa la consola (F12) y la API.</p>`;
        });
    }

        // Carga inicial y refresco automático
        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('monitoreo-table-container')) {
                 fetchAndRenderAdminTable();
                 setInterval(fetchAndRenderAdminTable, 30000); 
            }
        });
    </script>
</x-app-layout>