<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Reporte General de Viveros
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-white">Previsualización del Reporte</h2>
                    <a href="{{ route('admin.reportes.viveros.download') }}" class="inline-flex items-center px-4 py-2 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">
                        Descargar PDF
                    </a>
                </div>

                <div class="relative overflow-x-auto rounded-lg">
                    <table class="w-full text-sm text-left text-hydro-text-light">
                        <thead class="text-xs text-white uppercase bg-hydro-accent-bright/80">
                            <tr>
                                <th scope="col" class="px-6 py-4">Vivero</th>
                                <th scope="col" class="px-6 py-4">Dueño</th>
                                <th scope="col" class="px-6 py-4 text-center">Módulos Totales</th>
                                <th scope="col" class="px-6 py-4 text-center">Disponibles</th>
                                <th scope="col" class="px-6 py-4 text-center">Ocupados</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($viveros as $vivero)
                            <tr class="border-b border-hydro-dark">
                                <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">{{ $vivero->nombre }}</th>
                                <td class="px-6 py-4">{{ $vivero->user->full_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-center">{{ $vivero->modulos_count }}</td>
                                <td class="px-6 py-4 text-center">{{ $vivero->modulos_disponibles }}</td>
                                <td class="px-6 py-4 text-center">{{ $vivero->modulos_ocupados }}</td>
                            </tr>
                            @empty
                            <tr class="border-b border-hydro-dark">
                                <td colspan="5" class="px-6 py-4 text-center">No hay viveros registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>