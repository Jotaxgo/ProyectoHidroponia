<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Reporte del Módulo: <span class="text-hydro-accent-gold">{{ $modulo->codigo_identificador }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-white">Previsualización del Reporte</h2>
                    {{-- El botón de descarga ahora incluye los parámetros del filtro --}}
                    <a href="{{ route('admin.reportes.module.download', $request->all()) }}" class="inline-flex items-center px-4 py-2 bg-hydro-accent-gold ...">
                        Descargar PDF
                    </a>
                </div>

                <!-- {{-- Incluimos la misma tabla que usa el PDF para no repetir código --}}-->
                @include('admin.reportes.partials.module-report-table')

            </div>
        </div>
    </div>
</x-app-layout>