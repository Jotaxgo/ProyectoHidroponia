{{-- resources/views/admin/reportes/partials/module-report-table.blade.php --}}

@php
    // Si $isPdf no se pasó (por si acaso), asumimos que no es un PDF
    $isPdf = $isPdf ?? false;
@endphp

@if ($isPdf)
    {{-- SOLO SE INCLUYEN ESTOS ESTILOS AL GENERAR EL PDF --}}
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        .report-info { background-color: #f2f2f2; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; font-size: 9px; }
        .report-info strong { display: block; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: center; }
        thead th { background-color: #e2e8f0; font-weight: bold; font-size: 9px; }
        thead th[colspan="3"] { background-color: #cbd5e1; }
        tbody tr:nth-child(even) { background-color: #f8f8f8; }
        tbody td:nth-child(3), /* PH */
        tbody td:nth-child(6), /* EC */
        tbody td:nth-child(9), /* Temp */
        tbody td:nth-child(12), /* Luz */
        tbody td:nth-child(15) { /* Humedad */
            background-color: #edf2f7;
            font-weight: bold;
        }
        .no-data { text-align: center; color: #888; padding: 20px; }
    </style>
@endif

{{-- 
    Este div envuelve todo el reporte para 
    aplicar los estilos oscuros en la web.
--}}
<div class="{{ !$isPdf ? 'bg-white/90 backdrop-filter backdrop-blur-lg border border-[#e0e0e0] p-4 md:p-6 rounded-2xl' : '' }}" style="{{ !$isPdf ? 'box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);' : '' }}">
    
    {{-- Información del Reporte (con clases de Tailwind si NO es PDF) --}}
    <div class="report-info {{ !$isPdf ? 'bg-transparent border-0 p-0 mb-6' : '' }}">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm {{ !$isPdf ? 'text-[#555555]' : '' }}">
            <div>
                <strong class="{{ !$isPdf ? 'text-[#1a1a1a] font-semibold block uppercase tracking-wider text-xs' : '' }}">🔧 Módulo:</strong> 
                <span class="{{ !$isPdf ? 'font-bold text-[#1a1a1a] text-lg' : '' }}">{{ $modulo->codigo_identificador }} ({{ $modulo->vivero->nombre }})</span>
            </div>
            <div>
                <strong class="{{ !$isPdf ? 'text-[#1a1a1a] font-semibold block uppercase tracking-wider text-xs' : '' }}">📅 Periodo:</strong> 
                <span class="{{ !$isPdf ? 'text-[#1a1a1a] text-lg' : '' }}">{{ $fechaInicio }} al {{ $fechaFin }}</span>
            </div>
            <div>
                <strong class="{{ !$isPdf ? 'text-[#1a1a1a] font-semibold block uppercase tracking-wider text-xs' : '' }}">👤 Dueño:</strong> 
                <span class="{{ !$isPdf ? 'text-[#1a1a1a] text-lg' : '' }}">{{ $dueno->full_name }}</span>
            </div>
            <div>
                <strong class="{{ !$isPdf ? 'text-[#1a1a1a] font-semibold block uppercase tracking-wider text-xs' : '' }}">⚙️ Generado por:</strong> 
                <span class="{{ !$isPdf ? 'text-[#1a1a1a] text-lg' : '' }}">{{ $generadoPor }} ({{ $fechaGeneracion }})</span>
            </div>
        </div>
    </div>

    {{-- 
        ======================================================
        CORRECCIÓN RESPONSIVA: 
        Añadimos el div wrapper con 'overflow-x-auto' 
        alrededor de la tabla (solo si no es PDF).
        ======================================================
    --}}
    <div class="relative {{ !$isPdf ? 'overflow-x-auto rounded-xl border border-[#e0e0e0]' : '' }}">
        <table class="w-full text-sm text-left {{ !$isPdf ? 'text-[#555555]' : '' }}">
            <thead class="{{ !$isPdf ? 'text-xs text-[#1a1a1a] uppercase bg-gradient-to-r from-[#fafafa] to-[#f5f5f5] border-b border-[#e0e0e0]' : '' }}">
                <tr>
                    <th rowspan="2" class="px-4 py-3 {{ !$isPdf ? 'border-r border-[#e0e0e0] align-bottom font-semibold' : '' }}">Fecha y Hora</th>
                    <th colspan="3" class="px-4 py-3 text-center {{ !$isPdf ? 'border-r border-[#e0e0e0] font-semibold' : '' }}">pH</th>
                    <th colspan="3" class="px-4 py-3 text-center {{ !$isPdf ? 'border-r border-[#e0e0e0] font-semibold' : '' }}">EC (mS/cm)</th>
                    <th colspan="3" class="px-4 py-3 text-center {{ !$isPdf ? 'border-r border-[#e0e0e0] font-semibold' : '' }}">Temp (°C)</th>
                    <th colspan="3" class="px-4 py-3 text-center {{ !$isPdf ? 'border-r border-[#e0e0e0] font-semibold' : '' }}">Luz (lux)</th>
                    <th colspan="3" class="px-4 py-3 text-center {{ !$isPdf ? 'font-semibold' : '' }}">Humedad (%)</th>
                </tr>
                <tr class="{{ !$isPdf ? 'text-xs text-[#1a1a1a] uppercase bg-gradient-to-r from-[#fafafa] to-[#f5f5f5] border-b border-[#e0e0e0]' : '' }}">
                    {{-- Sub-cabeceras --}}
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-[#e0e0e0]' : '' }}">Min</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-[#e0e0e0]' : '' }}">Prom</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-[#e0e0e0]' : '' }}">Max</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-[#e0e0e0]' : '' }}">Min</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-[#e0e0e0]' : '' }}">Prom</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-[#e0e0e0]' : '' }}">Max</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-[#e0e0e0]' : '' }}">Min</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-[#e0e0e0]' : '' }}">Prom</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-[#e0e0e0]' : '' }}">Max</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-[#e0e0e0]' : '' }}">Min</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-[#e0e0e0]' : '' }}">Prom</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-[#e0e0e0]' : '' }}">Max</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-[#e0e0e0]' : '' }}">Min</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-[#e0e0e0]' : '' }}">Prom</th>
                    <th class="px-2 py-2 text-center font-medium">Max</th>
                </tr>
            </thead>
            <tbody class="{{ !$isPdf ? 'divide-y divide-[#e0e0e0]' : '' }}">
                @forelse ($lecturas as $lecturaHora)
                    <tr class="{{ !$isPdf ? 'bg-white hover:bg-[#ffdef0]/30 transition' : '' }}">
                        <td class="{{ !$isPdf ? 'px-4 py-2 font-medium text-[#1a1a1a] whitespace-nowrap border-r border-[#e0e0e0]' : '' }}">
                            {{ \Carbon\Carbon::parse($lecturaHora->hora_grupo)->format('d/m H:00') }}
                        </td>
                        {{-- PH --}}
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-[#e0e0e0]' : '' }}">{{ number_format($lecturaHora->ph_min, 2, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center bg-[#fafafa] border-r border-[#e0e0e0] font-semibold text-[#1a1a1a]' : '' }}">{{ number_format($lecturaHora->ph_avg, 2, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-[#e0e0e0]' : '' }}">{{ number_format($lecturaHora->ph_max, 2, ',', '.') }}</td>
                        {{-- EC --}}
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-[#e0e0e0]' : '' }}">{{ number_format($lecturaHora->ec_min, 2, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center bg-[#fafafa] border-r border-[#e0e0e0] font-semibold text-[#1a1a1a]' : '' }}">{{ number_format($lecturaHora->ec_avg, 2, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-[#e0e0e0]' : '' }}">{{ number_format($lecturaHora->ec_max, 2, ',', '.') }}</td>
                        {{-- Temp --}}
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-[#e0e0e0]' : '' }}">{{ number_format($lecturaHora->temperatura_min, 1, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center bg-[#fafafa] border-r border-[#e0e0e0] font-semibold text-[#1a1a1a]' : '' }}">{{ number_format($lecturaHora->temperatura_avg, 1, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-[#e0e0e0]' : '' }}">{{ number_format($lecturaHora->temperatura_max, 1, ',', '.') }}</td>
                        {{-- Luz --}}
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-[#e0e0e0]' : '' }}">{{ number_format($lecturaHora->luz_min, 0, '', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center bg-[#fafafa] border-r border-[#e0e0e0] font-semibold text-[#1a1a1a]' : '' }}">{{ number_format($lecturaHora->luz_avg, 0, '', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-[#e0e0e0]' : '' }}">{{ number_format($lecturaHora->luz_max, 0, '', '.') }}</td>
                        {{-- Humedad --}}
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-[#e0e0e0]' : '' }}">{{ number_format($lecturaHora->humedad_min, 1, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center bg-[#fafafa] border-r border-[#e0e0e0] font-semibold text-[#1a1a1a]' : '' }}">{{ number_format($lecturaHora->humedad_avg, 1, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center' : '' }}">{{ number_format($lecturaHora->humedad_max, 1, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="16" class="no-data {{ !$isPdf ? 'px-6 py-4 text-center text-[#999999]' : '' }}">
                            📭 No se encontraron lecturas registradas para este módulo en el período seleccionado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>