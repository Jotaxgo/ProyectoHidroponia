{{-- resources/views/admin/reportes/partials/module-report-table.blade.php --}}

@php
    // Si $isPdf no se pasó (por si acaso), asumimos que no es un PDF
    $isPdf = $isPdf ?? false;
@endphp

<div class="{{ !$isPdf ? 'bg-white/90 backdrop-filter backdrop-blur-lg border border-[#e0e0e0] p-4 md:p-6 rounded-2xl' : '' }}" style="{{ !$isPdf ? 'box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);' : '' }}">
    
    @if (!$isPdf)
        {{-- Información del Reporte (solo para la web) --}}
        <div class="report-info bg-transparent border-0 p-0 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-[#555555]">
                <div>
                    <strong class="text-[#1a1a1a] font-semibold block uppercase tracking-wider text-xs">🔧 Módulo:</strong> 
                    <span class="font-bold text-[#1a1a1a] text-lg">{{ $modulo->codigo_identificador }} ({{ $modulo->vivero->nombre }})</span>
                </div>
                <div>
                    <strong class="text-[#1a1a1a] font-semibold block uppercase tracking-wider text-xs">📅 Periodo:</strong> 
                    <span class="text-[#1a1a1a] text-lg">{{ $fechaInicio }} al {{ $fechaFin }}</span>
                </div>
                <div>
                    <strong class="text-[#1a1a1a] font-semibold block uppercase tracking-wider text-xs">👤 Dueño:</strong> 
                    <span class="text-[#1a1a1a] text-lg">{{ $dueno->full_name }}</span>
                </div>
                <div>
                    <strong class="text-[#1a1a1a] font-semibold block uppercase tracking-wider text-xs">⚙️ Generado por:</strong> 
                    <span class="text-[#1a1a1a] text-lg">{{ $generadoPor }} ({{ $fechaGeneracion }})</span>
                </div>
            </div>
        </div>
    @endif

    <div class="relative {{ !$isPdf ? 'overflow-x-auto rounded-xl border border-[#e0e0e0]' : '' }}">
        <table class="w-full text-sm text-left {{ !$isPdf ? 'text-[#555555]' : '' }}">
            <thead class="{{ !$isPdf ? 'text-xs text-[#1a1a1a] uppercase bg-gradient-to-r from-[#fafafa] to-[#f5f5f5] border-b border-[#e0e0e0]' : '' }}">
                <tr>
                    <th class="px-4 py-3 {{ !$isPdf ? 'border-r border-[#e0e0e0] align-bottom font-semibold' : '' }}">Fecha y Hora</th>
                    <th class="px-4 py-3 text-center {{ !$isPdf ? 'border-r border-[#e0e0e0] font-semibold' : '' }}">pH (Prom)</th>
                    <th class="px-4 py-3 text-center {{ !$isPdf ? 'border-r border-[#e0e0e0] font-semibold' : '' }}">EC (Prom)</th>
                    <th class="px-4 py-3 text-center {{ !$isPdf ? 'border-r border-[#e0e0e0] font-semibold' : '' }}">Temp (°C) (Prom)</th>
                    <th class="px-4 py-3 text-center {{ !$isPdf ? 'border-r border-[#e0e0e0] font-semibold' : '' }}">Luz (lux) (Prom)</th>
                    <th class="px-4 py-3 text-center {{ !$isPdf ? 'font-semibold' : '' }}">Humedad (%) (Prom)</th>
                </tr>
            </thead>
            <tbody class="{{ !$isPdf ? 'divide-y divide-[#e0e0e0]' : '' }}">
                @forelse ($lecturas as $lecturaHora)
                    <tr class="{{ !$isPdf ? 'bg-white hover:bg-[#ffdef0]/30 transition' : '' }}">
                        <td class="{{ !$isPdf ? 'px-4 py-2 font-medium text-[#1a1a1a] whitespace-nowrap border-r border-[#e0e0e0]' : '' }}">
                            {{ \Carbon\Carbon::parse($lecturaHora->hora_grupo)->format('d/m H:00') }}
                        </td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-[#e0e0e0]' : '' }}">{{ number_format($lecturaHora->ph_avg, 2, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-[#e0e0e0]' : '' }}">{{ number_format($lecturaHora->ec_avg, 2, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-[#e0e0e0]' : '' }}">{{ number_format($lecturaHora->temperatura_avg, 1, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-[#e0e0e0]' : '' }}">{{ number_format($lecturaHora->luz_avg, 0, '', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center' : '' }}">{{ number_format($lecturaHora->humedad_avg, 1, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="no-data {{ !$isPdf ? 'px-6 py-4 text-center text-[#999999]' : '' }}">
                            📭 No se encontraron lecturas registradas para este módulo en el período seleccionado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>