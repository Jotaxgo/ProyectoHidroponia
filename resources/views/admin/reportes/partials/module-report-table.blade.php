<div class="overflow-x-auto relative shadow-md sm:rounded-lg mt-6 border border-gray-700">
    {{-- Información del Reporte --}}
    <div class="p-4 bg-gray-800 text-sm text-gray-300 grid grid-cols-2 gap-4">
        <div>
            <span class="font-semibold text-gray-400 block">Módulo:</span>
            <span class="font-bold text-white">{{ $modulo->codigo_identificador }}</span>
            <span class="text-xs text-gray-500 ml-1">({{ $modulo->vivero->nombre }})</span>
        </div>
        <div>
            <span class="font-semibold text-gray-400 block">Periodo:</span>
            <span class="text-white">{{ $fechaInicio }} al {{ $fechaFin }}</span>
        </div>
        <div>
            <span class="font-semibold text-gray-400 block">Dueño:</span>
            <span class="text-white">{{ $dueno->full_name }}</span>
        </div>
        <div>
            <span class="font-semibold text-gray-400 block">Generado por:</span>
            <span class="text-white">{{ $generadoPor }} ({{ $fechaGeneracion }})</span>
        </div>
    </div>

    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs text-gray-400 uppercase bg-gray-700">
            <tr>
                {{-- Cabecera Principal --}}
                <th scope="col" class="px-4 py-3 border-r border-gray-600 align-bottom" rowspan="2">Fecha y Hora</th>
                <th scope="col" class="px-4 py-3 text-center border-r border-gray-600" colspan="3">pH</th>
                <th scope="col" class="px-4 py-3 text-center border-r border-gray-600" colspan="3">EC (mS/cm)</th>
                <th scope="col" class="px-4 py-3 text-center border-r border-gray-600" colspan="3">Temp (°C)</th>
                <th scope="col" class="px-4 py-3 text-center" colspan="3">Luz (lux)</th>
                {{-- (Añade Humedad si la tienes) --}}
            </tr>
            <tr>
                {{-- Sub-cabeceras para Min/Prom/Max --}}
                <th scope="col" class="px-2 py-2 text-center font-medium border-r border-gray-600">Min</th>
                <th scope="col" class="px-2 py-2 text-center font-medium border-r border-gray-600">Prom</th>
                <th scope="col" class="px-2 py-2 text-center font-medium border-r border-gray-600">Max</th>
                <th scope="col" class="px-2 py-2 text-center font-medium border-r border-gray-600">Min</th>
                <th scope="col" class="px-2 py-2 text-center font-medium border-r border-gray-600">Prom</th>
                <th scope="col" class="px-2 py-2 text-center font-medium border-r border-gray-600">Max</th>
                <th scope="col" class="px-2 py-2 text-center font-medium border-r border-gray-600">Min</th>
                <th scope="col" class="px-2 py-2 text-center font-medium border-r border-gray-600">Prom</th>
                <th scope="col" class="px-2 py-2 text-center font-medium border-r border-gray-600">Max</th>
                <th scope="col" class="px-2 py-2 text-center font-medium border-r border-gray-600">Min</th>
                <th scope="col" class="px-2 py-2 text-center font-medium border-r border-gray-600">Prom</th>
                <th scope="col" class="px-2 py-2 text-center font-medium">Max</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lecturas as $lecturaHora)
                <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-600">
                    {{-- Formateamos la hora del grupo --}}
                    <td class="px-4 py-2 font-medium text-gray-300 whitespace-nowrap border-r border-gray-600">
                        {{ \Carbon\Carbon::parse($lecturaHora->hora_grupo)->format('d/m H:00') }}
                    </td>
                    {{-- Mostramos Min/Prom/Max para cada sensor --}}
                    <td class="px-2 py-2 text-center border-r border-gray-600">{{ number_format($lecturaHora->ph_min, 2) }}</td>
                    <td class="px-2 py-2 text-center bg-gray-700 border-r border-gray-600 font-semibold text-white">{{ number_format($lecturaHora->ph_avg, 2) }}</td>
                    <td class="px-2 py-2 text-center border-r border-gray-600">{{ number_format($lecturaHora->ph_max, 2) }}</td>
                    <td class="px-2 py-2 text-center border-r border-gray-600">{{ number_format($lecturaHora->ec_min, 2) }}</td>
                    <td class="px-2 py-2 text-center bg-gray-700 border-r border-gray-600 font-semibold text-white">{{ number_format($lecturaHora->ec_avg, 2) }}</td>
                    <td class="px-2 py-2 text-center border-r border-gray-600">{{ number_format($lecturaHora->ec_max, 2) }}</td>
                    <td class="px-2 py-2 text-center border-r border-gray-600">{{ number_format($lecturaHora->temperatura_min, 1) }}</td>
                    <td class="px-2 py-2 text-center bg-gray-700 border-r border-gray-600 font-semibold text-white">{{ number_format($lecturaHora->temperatura_avg, 1) }}</td>
                    <td class="px-2 py-2 text-center border-r border-gray-600">{{ number_format($lecturaHora->temperatura_max, 1) }}</td>
                    <td class="px-2 py-2 text-center border-r border-gray-600">{{ number_format($lecturaHora->luz_min, 0) }}</td>
                    <td class="px-2 py-2 text-center bg-gray-700 border-r border-gray-600 font-semibold text-white">{{ number_format($lecturaHora->luz_avg, 0) }}</td>
                    <td class="px-2 py-2 text-center">{{ number_format($lecturaHora->luz_max, 0) }}</td>
                </tr>
            @empty
                <tr>
                    {{-- Ajustamos el colspan al nuevo número de columnas --}}
                    <td colspan="13" class="px-6 py-4 text-center text-gray-500">
                        No se encontraron lecturas registradas para este módulo en el período seleccionado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>