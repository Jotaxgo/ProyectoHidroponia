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
            <div class="bg-hydro-card p-6 rounded-lg shadow-xl overflow-x-auto">
                <h2 class="text-2xl font-bold text-white mb-4">
                    Monitoreo en Tiempo Real
                </h2>
                
                {{-- Contenedor donde se dibujará la tabla de monitoreo --}}
                <div id="monitoreo-table-container">
                    <p class="text-gray-400 animate-pulse">Cargando datos de módulos...</p>
                </div>
            </div>


            {{-- ====================================================== --}}
            {{-- SECCIÓN 2: GESTIÓN DE MÓDULOS (TU CÓDIGO EXISTENTE) --}}
            {{-- ====================================================== --}}
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">

                {{-- Tu código de "Volver" --}}
                @if(Auth::user()->role->nombre_rol == 'Admin')
                    <a href="{{ route('admin.viveros.index') }}" class="text-gray-400 hover:text-white">
                        &larr; Volver a la Lista de Viveros
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white">
                        &larr; Volver a mi Dashboard
                    </a>
                @endif

                {{-- Tu código de cabecera de la tabla de gestión --}}
                <div class="flex justify-between items-center mb-6 mt-4">
                    <h2 class="text-2xl font-bold text-white">Lista de Módulos (Gestión)</h2>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.viveros.modulos.trash', $vivero) }}" class="text-gray-400 hover:text-white">Ver Papelera 🗑️</a>
                        <a href="{{ route('admin.viveros.modulos.create', $vivero) }}" class="inline-flex items-center px-4 py-2 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">
                            Añadir Nuevo Módulo
                        </a>
                    </div>
                </div>

                {{-- Tu tabla de gestión CRUD existente --}}
                <div class="relative overflow-x-auto rounded-lg">
                    <table class="w-full text-sm text-left text-hydro-text-light">
                        <thead class="text-xs text-white uppercase bg-hydro-accent-bright/80">
                            <tr>
                                <th scope="col" class="px-6 py-4">Código</th>
                                <th scope="col" class="px-6 py-4">Device ID</th>
                                <th scope="col" classs="px-6 py-4">Estado</th>
                                <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($modulos as $modulo)
                            <tr class="border-b border-hydro-dark hover:bg-hydro-dark/50">
                                <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">{{ $modulo->codigo_identificador }}</th>
                                <td class="px-6 py-4">{{ $modulo->hardware_info['device_id'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($modulo->estado == 'Disponible') bg-green-500/20 text-green-300
                                        @elseif($modulo->estado == 'Ocupado') bg-yellow-500/20 text-yellow-300
                                        @else bg-red-500/20 text-red-300 @endif">
                                        {{ $modulo->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        @if($modulo->estado == 'Disponible')
                                            <a href="{{ route('admin.viveros.modulos.startCultivoForm', [$vivero, $modulo]) }}" class="inline-flex items-center px-2.5 py-1.5 bg-hydro-accent-bright/80 text-white rounded-md text-xs hover:bg-hydro-accent-bright transition">
                                                Iniciar Cultivo
                                            </a>
                                        @endif

                                        <a href="{{ route('admin.viveros.modulos.edit', [$vivero, $modulo]) }}" class="inline-flex items-center px-2.5 py-1.5 bg-hydro-accent-gold/20 text-hydro-accent-gold rounded-md text-xs hover:bg-hydro-accent-gold/40 transition">Editar</a>

                                        <form action="{{ route('admin.viveros.modulos.destroy', [$vivero, $modulo]) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-red-500/20 text-red-300 rounded-md text-xs hover:bg-red-500/40 transition">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="border-b border-hydro-dark">
                                <td colspan="4" class="px-6 py-4 text-center">Este vivero aún no tiene módulos.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ====================================================== --}}
    {{-- SCRIPT PARA LA TABLA DE MONITOREO (MODIFICADO) --}}
    {{-- ====================================================== --}}
    <script>
        // Función para obtener los estilos de la alerta (colores)
        function getAlertStyles(estado) {
            // ... (esta función no cambia) ...
            switch (estado) {
                case 'CRÍTICO': return { text: 'text-white', bg: 'bg-red-600' };
                case 'ADVERTENCIA': return { text: 'text-gray-900', bg: 'bg-yellow-400' };
                case 'OFFLINE': return { text: 'text-gray-900', bg: 'bg-gray-400' };
                default: return { text: 'text-white', bg: 'bg-green-600' }; // Estado 'OK'
            }
        }

        // Función principal para buscar y dibujar la tabla
        function fetchAndRenderOwnerTable() {
            const container = document.getElementById('monitoreo-table-container');
            if (!container) return;

            const viveroId = {{ $vivero->id }};
            // const apiUrl = window.location.origin + `/api/owner/vivero/${viveroId}/latest-data`;
            const apiUrl = window.location.origin + `/admin/vivero/${viveroId}/latest-data`;

            if (container.querySelector('table')) {
                 container.querySelector('tbody').classList.add('opacity-50', 'transition-opacity');
            } else {
                container.innerHTML = `<p class="text-gray-400 animate-pulse">Refrescando datos...</p>`;
            }

            // ==================================================
            // === ¡AQUÍ ESTÁ LA CORRECCIÓN! ===
            // Añadimos las cabeceras 'Accept' y 'X-CSRF-TOKEN'
            // ==================================================
            fetch(apiUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                // Ahora, si falla la autenticación (401 o 403), lo manejamos
                if (response.status === 401 || response.status === 403) {
                    throw new Error('No autorizado. Revisa los permisos o inicia sesión.');
                }
                if (!response.ok) {
                    throw new Error('Error de conexión con la API: ' + response.statusText);
                }
                // Si la respuesta es OK (200), la leemos como JSON
                return response.json(); 
            })
            .then(data => {
                // Si la API devuelve un mensaje de error (como "Vivero no encontrado")
                if (data.message) {
                    throw new Error(data.message);
                }

                // Si el vivero no tiene módulos
                if (data.length === 0) {
                    container.innerHTML = `<p class="text-gray-400">Este vivero no tiene módulos activos (en estado 'Ocupado').</p>`;
                    return;
                }

                // --- Construcción de la tabla HTML ---
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
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">`;

                // Iteramos sobre los datos recibidos
                data.forEach(item => {
                    const styles = getAlertStyles(item.estado_alerta);
                    const isOffline = item.estado_alerta === 'OFFLINE';
                    
                    const tiempoReporte = isOffline ? (item.minutos_offline === null ? 'Nunca' : `Hace ${item.minutos_offline}+ min`) : item.ultima_lectura;
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
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${styles.bg} ${styles.text}">${item.estado_alerta}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">${tiempoReporte}</td>
                        </tr>`;
                });

                tableHtml += `</tbody></table>`;
                container.innerHTML = tableHtml;
            })
            .catch(error => {
                console.error('Error:', error);
                // Ahora mostramos el error de autenticación o de conexión
                container.innerHTML = `<p class="text-red-400">Error al cargar la tabla: ${error.message}</p>`;
            });
        }

        // --- Carga inicial y refresco automático ---
        document.addEventListener('DOMContentLoaded', () => {
            // Asegurarnos de que el script se ejecute solo si el contenedor existe
            if (document.getElementById('monitoreo-table-container')) {
                fetchAndRenderOwnerTable(); // Cargar al abrir la página
                setInterval(fetchAndRenderOwnerTable, 30000); // Refrescar cada 30 segundos
            }
        });
    </script>
</x-app-layout>