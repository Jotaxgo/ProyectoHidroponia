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
<div class="{{ !$isPdf ? 'bg-hydro-dark border border-gray-700 p-4 md:p-6 rounded-lg' : '' }}">
    
    {{-- Información del Reporte (con clases de Tailwind si NO es PDF) --}}
    <div class="report-info {{ !$isPdf ? 'bg-transparent border-0 p-0 mb-6' : '' }}">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm {{ !$isPdf ? 'text-gray-300' : '' }}">
            <div>
                <strong class="{{ !$isPdf ? 'text-gray-400 font-semibold block uppercase tracking-wider' : '' }}">Módulo:</strong> 
                <span class="{{ !$isPdf ? 'font-bold text-white text-lg' : '' }}">{{ $modulo->codigo_identificador }} ({{ $modulo->vivero->nombre }})</span>
            </div>
            <div>
                <strong class="{{ !$isPdf ? 'text-gray-400 font-semibold block uppercase tracking-wider' : '' }}">Periodo:</strong> 
                <span class="{{ !$isPdf ? 'text-white text-lg' : '' }}">{{ $fechaInicio }} al {{ $fechaFin }}</span>
            </div>
            <div>
                <strong class="{{ !$isPdf ? 'text-gray-400 font-semibold block uppercase tracking-wider' : '' }}">Dueño:</strong> 
                <span class="{{ !$isPdf ? 'text-white text-lg' : '' }}">{{ $dueno->full_name }}</span>
            </div>
            <div>
                <strong class="{{ !$isPdf ? 'text-gray-400 font-semibold block uppercase tracking-wider' : '' }}">Generado por:</strong> 
                <span class="{{ !$isPdf ? 'text-white text-lg' : '' }}">{{ $generadoPor }} ({{ $fechaGeneracion }})</span>
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
    <div class="relative {{ !$isPdf ? 'overflow-x-auto shadow-md sm:rounded-lg border border-gray-700' : '' }}">
        <table class="w-full text-sm text-left {{ !$isPdf ? 'text-gray-400' : '' }}">
            <thead class="{{ !$isPdf ? 'text-xs text-gray-400 uppercase bg-gray-700' : '' }}">
                <tr>
                    <th rowspan="2" class="px-4 py-3 {{ !$isPdf ? 'border-r border-gray-600 align-bottom' : '' }}">Fecha y Hora</th>
                    <th colspan="3" class="px-4 py-3 text-center {{ !$isPdf ? 'border-r border-gray-600' : '' }}">pH</th>
                    <th colspan="3" class="px-4 py-3 text-center {{ !$isPdf ? 'border-r border-gray-600' : '' }}">EC (mS/cm)</th>
                    <th colspan="3" class="px-4 py-3 text-center {{ !$isPdf ? 'border-r border-gray-600' : '' }}">Temp (°C)</th>
                    <th colspan="3" class="px-4 py-3 text-center {{ !$isPdf ? 'border-r border-gray-600' : '' }}">Luz (lux)</th>
                    <th colspan="3" class="px-4 py-3 text-center">Humedad (%)</th>
                </tr>
                <tr class="{{ !$isPdf ? 'text-xs text-gray-400 uppercase bg-gray-700' : '' }}">
                    {{-- Sub-cabeceras --}}
                    <th classa="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-gray-600' : '' }}">Min</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-gray-600' : '' }}">Prom</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-gray-600' : '' }}">Max</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-gray-600' : '' }}">Min</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-gray-600' : '' }}">Prom</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-gray-600' : '' }}">Max</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-gray-600' : '' }}">Min</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-gray-600' : '' }}">Prom</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-gray-600' : '' }}">Max</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-gray-600' : '' }}">Min</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-gray-600' : '' }}">Prom</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-gray-600' : '' }}">Max</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-gray-600' : '' }}">Min</th>
                    <th class="px-2 py-2 text-center font-medium {{ !$isPdf ? 'border-r border-gray-600' : '' }}">Prom</th>
                    <th class="px-2 py-2 text-center font-medium">Max</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lecturas as $lecturaHora)
                    <tr class="{{ !$isPdf ? 'bg-gray-800 border-b border-gray-700 hover:bg-gray-600' : '' }}">
                        <td class="{{ !$isPdf ? 'px-4 py-2 font-medium text-gray-300 whitespace-nowrap border-r border-gray-600' : '' }}">
                            {{ \Carbon\Carbon::parse($lecturaHora->hora_grupo)->format('d/m H:00') }}
                        </td>
                        {{-- PH --}}
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-gray-600' : '' }}">{{ number_format($lecturaHora->ph_min, 2, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center bg-gray-700 border-r border-gray-600 font-semibold text-white' : '' }}">{{ number_format($lecturaHora->ph_avg, 2, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-gray-600' : '' }}">{{ number_format($lecturaHora->ph_max, 2, ',', '.') }}</td>
                        {{-- EC --}}
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-gray-600' : '' }}">{{ number_format($lecturaHora->ec_min, 2, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center bg-gray-700 border-r border-gray-600 font-semibold text-white' : '' }}">{{ number_format($lecturaHora->ec_avg, 2, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-gray-600' : '' }}">{{ number_format($lecturaHora->ec_max, 2, ',', '.') }}</td>
                        {{-- Temp --}}
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-gray-600' : '' }}">{{ number_format($lecturaHora->temperatura_min, 1, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center bg-gray-700 border-r border-gray-600 font-semibold text-white' : '' }}">{{ number_format($lecturaHora->temperatura_avg, 1, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-gray-600' : '' }}">{{ number_format($lecturaHora->temperatura_max, 1, ',', '.') }}</td>
                        {{-- Luz --}}
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-gray-600' : '' }}">{{ number_format($lecturaHora->luz_min, 0, '', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center bg-gray-700 border-r border-gray-600 font-semibold text-white' : '' }}">{{ number_format($lecturaHora->luz_avg, 0, '', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-gray-600' : '' }}">{{ number_format($lecturaHora->luz_max, 0, '', '.') }}</td>
                        {{-- Humedad --}}
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center border-r border-gray-600' : '' }}">{{ number_format($lecturaHora->humedad_min, 1, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center bg-gray-700 border-r border-gray-600 font-semibold text-white' : '' }}">{{ number_format($lecturaHora->humedad_avg, 1, ',', '.') }}</td>
                        <td class="{{ !$isPdf ? 'px-2 py-2 text-center' : '' }}">{{ number_format($lecturaHora->humedad_max, 1, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="16" class="no-data {{ !$isPdf ? 'px-6 py-4 text-center text-gray-500' : '' }}">
                            No se encontraron lecturas registradas para este módulo en el período seleccionado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>