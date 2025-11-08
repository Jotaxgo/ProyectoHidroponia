<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            📊 Reporte del Módulo: <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">{{ $modulo->codigo_identificador }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">

                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Previsualización del Reporte</h2>
                    {{-- El botón de descarga ahora incluye los parámetros del filtro --}}
                    <a href="{{ route('admin.reportes.module.download', $request->all()) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                        📥 Descargar PDF
                    </a>
                </div>

                <!-- {{-- Incluimos la misma tabla que usa el PDF para no repetir código --}}-->
                @include('admin.reportes.partials.module-report-table')

            </div>
        </div>
    </div>
</x-app-layout>