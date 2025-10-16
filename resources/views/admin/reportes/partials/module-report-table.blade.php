<div class="text-hydro-text-light space-y-2 mb-6">
    <p><strong>Módulo:</strong> {{ $modulo->codigo_identificador }}</p>
    <p><strong>Vivero:</strong> {{ $modulo->vivero->nombre }}</p>
    <p><strong>Dueño:</strong> {{ $modulo->vivero->user->full_name }}</p>
    <p><strong>Periodo:</strong> {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}</p>
</div>

<div class="relative overflow-x-auto rounded-lg">
    <table class="w-full text-sm text-left text-hydro-text-light">
        <thead class="text-xs text-white uppercase bg-hydro-accent-bright/80">
            <tr>
                <th scope="col" class="px-6 py-4">Fecha</th>
                <th scope="col" class="px-6 py-4">Temperatura (°C)</th>
                <th scope="col" class="px-6 py-4">Nivel de pH</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($datos as $lectura)
                <tr class="border-b border-hydro-dark">
                    <td class="px-6 py-4">{{ $lectura['fecha'] }}</td>
                    <td class="px-6 py-4">{{ $lectura['temperatura'] }}</td>
                    <td class="px-6 py-4">{{ $lectura['ph'] }}</td>
                </tr>
            @empty
                <tr class="border-b border-hydro-dark">
                    <td colspan="3" class="px-6 py-4 text-center">No hay datos para el periodo seleccionado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>