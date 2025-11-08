<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            📊 <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Reporte General de Viveros</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">

                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Previsualización del Reporte</h2>
                    <a href="{{ route('admin.reportes.viveros.download') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white rounded-lg text-xs font-semibold hover:shadow-lg transition">
                        📥 Descargar PDF
                    </a>
                </div>

                <div class="relative overflow-x-auto rounded-xl border border-[#e0e0e0]">
                    <table class="w-full text-sm">
                        <thead class="bg-gradient-to-r from-[#fafafa] to-[#f5f5f5] border-b border-[#e0e0e0]">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">🌱 Vivero</th>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">👤 Dueño</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">🔧 Total</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">✅ Disponibles</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">🌱 Ocupados</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e0e0e0]">
                            @forelse ($viveros as $vivero)
                            <tr class="hover:bg-[#ffdef0]/30 transition">
                                <td class="px-6 py-4 font-semibold text-[#1a1a1a]">{{ $vivero->nombre }}</td>
                                <td class="px-6 py-4 text-[#555555]">{{ $vivero->user->full_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#e0e0e0] font-bold text-[#1a1a1a]">{{ $vivero->modulos_count }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#96d900]/20 text-[#6b9b00] font-semibold text-xs">✅ {{ $vivero->modulos_disponibles }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-400/20 text-amber-700 font-semibold text-xs">🌱 {{ $vivero->modulos_ocupados }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-[#999999]">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-2xl">📭</span>
                                        <p>No hay viveros registrados.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>