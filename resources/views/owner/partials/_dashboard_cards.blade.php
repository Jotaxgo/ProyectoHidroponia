{{-- Sección de Estadísticas de Módulos --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white/90 backdrop-filter backdrop-blur-lg rounded-2xl p-6 border border-[#e0e0e0]" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
        <p class="text-sm font-medium text-[#555555] mb-2">🔧 Módulos Totales</p>
        <p class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-white/90 backdrop-filter backdrop-blur-lg rounded-2xl p-6 border border-[#e0e0e0]" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
        <p class="text-sm font-medium text-[#555555] mb-2">✅ Módulos Disponibles</p>
        <p class="text-3xl font-bold text-[#96d900]">{{ $stats['disponibles'] }}</p>
    </div>
    <div class="bg-white/90 backdrop-filter backdrop-blur-lg rounded-2xl p-6 border border-[#e0e0e0]" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
        <p class="text-sm font-medium text-[#555555] mb-2">🌾 Módulos Ocupados</p>
        <p class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">{{ $stats['ocupados'] }}</p>
    </div>
    <div class="bg-white/90 backdrop-filter backdrop-blur-lg rounded-2xl p-6 border border-[#e0e0e0]" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
        <p class="text-sm font-medium text-[#555555] mb-2">🛠️ En Mantenimiento</p>
        <p class="text-3xl font-bold text-yellow-500">{{ $stats['mantenimiento'] }}</p>
    </div>
</div>

<div class="bg-white/80 backdrop-blur-lg rounded-2xl p-6 border" style="border-color: var(--border); box-shadow: var(--shadow-sm);">
    <h2 class="text-2xl font-bold text-text-dark mb-6">Mis Viveros</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($viveros as $vivero)
            @php
                $ocupados = $vivero->modulos->where('estado', 'Ocupado')->count();
                $disponibles = $vivero->modulos->where('estado', 'Disponible')->count();
                $mantenimiento = $vivero->modulos->where('estado', 'Mantenimiento')->count();
                $totalModulos = $vivero->modulos_count;
                $porcentajeOcupacion = $totalModulos > 0 ? ($ocupados / $totalModulos) * 100 : 0;

                // Determinar estado general del vivero
                $hasCritico = $vivero->modulos->contains(function ($modulo) {
                    return $modulo->latestLectura && $modulo->latestLectura->estado_alerta === 'CRÍTICO';
                });
                $hasAdvertencia = $vivero->modulos->contains(function ($modulo) {
                    return $modulo->latestLectura && $modulo->latestLectura->estado_alerta === 'ADVERTENCIA';
                });

                if ($hasCritico) {
                    $estadoGeneral = 'CRÍTICO';
                    $estadoColor = 'bg-red-500';
                } elseif ($hasAdvertencia) {
                    $estadoGeneral = 'ADVERTENCIA';
                    $estadoColor = 'bg-yellow-500';
                } else {
                    $estadoGeneral = 'OK';
                    $estadoColor = 'bg-green-500';
                }
            @endphp
            <div class="bg-white/50 rounded-xl p-5 border flex flex-col" style="border-color: var(--border);">
                <!-- Header de la tarjeta -->
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-bold text-lg text-text-dark">{{ $vivero->nombre }}</h3>
                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $estadoColor }} text-white">
                        {{ $estadoGeneral }}
                    </span>
                </div>

                <!-- Barra de Progreso -->
                <div class="mb-4">
                    <div class="flex justify-between text-xs mb-1" style="color: var(--text-muted);">
                        <span>Ocupación de Módulos</span>
                        <span>{{ $ocupados }} / {{ $totalModulos }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full" style="width: {{ $porcentajeOcupacion }}%; background-color: var(--strawberry);"></div>
                    </div>
                </div>

                <!-- Mini-estadísticas -->
                <div class="flex-grow space-y-2 text-sm mb-5">
                    <div class="flex justify-between">
                        <span style="color: var(--text-muted);">Disponibles:</span>
                        <span class="font-semibold text-green-600">{{ $disponibles }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color: var(--text-muted);">En Mantenimiento:</span>
                        <span class="font-semibold text-yellow-600">{{ $mantenimiento }}</span>
                    </div>
                </div>

                <!-- Botón de Acción -->
                <div class="mt-auto">
                    <a href="{{ route('admin.viveros.modulos.index', $vivero) }}" 
                       class="block w-full text-center px-4 py-2 rounded-lg font-semibold text-sm text-white transition"
                       style="background-color: var(--strawberry); box-shadow: var(--shadow-sm);"
                       onmouseover="this.style.backgroundColor='var(--strawberry-dark)'"
                       onmouseout="this.style.backgroundColor='var(--strawberry)'">
                        Gestionar Módulos
                    </a>
                </div>
            </div>
        @empty
            <p class="text-text-muted col-span-full">Aún no tienes viveros asignados. Contacta a un administrador.</p>
        @endforelse
    </div>
</div>
