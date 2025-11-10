<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            🌱 <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Dashboard del Propietario</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" id="dashboard-content-container">
            {{-- El contenido inicial se carga desde el parcial --}}
            @include('owner.partials._dashboard_cards')
        </div>
    </div>

    <script>
        function updateDashboardContent() {
            const container = document.getElementById('dashboard-content-container');
            if (!container) return;

            // Muestra una pequeña animación de "cargando"
            container.style.opacity = '0.5';
            
            fetch(`{{ route('owner.dashboard.content') }}`, {
                headers: { 
                    'Accept': 'text/html', // Esperamos HTML
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.status === 419 || response.status === 401) {
                    window.location.reload(); 
                    throw new Error('Sesión expirada.'); 
                }
                if (!response.ok) throw new Error('Error al refrescar el dashboard.');
                return response.text(); // Obtenemos el HTML como texto
            })
            .then(html => {
                container.innerHTML = html; // Reemplazamos el contenido
                container.style.opacity = '1'; // Restauramos la opacidad
            })
            .catch(error => {
                console.error('Error en la actualización del dashboard:', error);
                container.style.opacity = '1'; // Restauramos opacidad incluso si hay error
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Actualizamos cada 30 segundos
            setInterval(updateDashboardContent, 30000);
        });
    </script>
</x-app-layout>