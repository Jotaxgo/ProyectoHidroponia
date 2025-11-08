<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Papelera de Módulos en: <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">{{ $vivero->nombre }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">

                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Módulos Eliminados</h2>
                    <a href="{{ route('admin.viveros.modulos.index', $vivero) }}" class="inline-flex items-center px-4 py-2 text-[#555555] hover:text-[#9c0000] transition font-medium text-sm">
                        ← Volver
                    </a>
                </div>

                <div class="relative overflow-x-auto rounded-xl border border-[#e0e0e0]">
                    <table class="w-full text-sm">
                        <thead class="bg-gradient-to-r from-[#fafafa] to-[#f5f5f5] border-b border-[#e0e0e0]">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Código</th>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Device ID</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e0e0e0]">
                            @forelse ($trashedModulos as $modulo)
                            <tr class="hover:bg-[#ffdef0]/30 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg">🔧</span>
                                        <span class="font-semibold text-[#1a1a1a]">{{ $modulo->codigo_identificador }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[#555555]">{{ $modulo->hardware_info['device_id'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('admin.viveros.modulos.restore', [$vivero, $modulo->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-[#96d900]/20 text-[#6b9b00] rounded-lg text-xs font-semibold hover:bg-[#96d900]/30 transition">
                                                ↩️ Restaurar
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.viveros.modulos.forceDelete', [$vivero, $modulo->id]) }}" method="POST" class="inline" onsubmit="return confirm('¿ELIMINACIÓN PERMANENTE? Esta acción no se puede deshacer.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500/20 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-500/30 transition">
                                                🚫 Borrar Permanente
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-[#999999]">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-2xl">✨</span>
                                        <p>La papelera está vacía.</p>
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