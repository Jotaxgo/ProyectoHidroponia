<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            📊 <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Reporte General de Viveros</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Formulario de Filtro --}}
            <div class="mb-8 p-6 bg-white/50 backdrop-filter backdrop-blur-lg rounded-2xl border border-gray-200">
                <form action="{{ route('admin.reportes.viveros.show') }}" method="GET" class="flex items-center space-x-4">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700">Filtrar por Usuario:</label>
                        <select name="user_id" id="user_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-[#9c0000] focus:border-[#9c0000] sm:text-sm rounded-md">
                            <option value="">-- Todos los Usuarios --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request()->get('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pt-5">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                            Filtrar
                        </button>
                        <a href="{{ route('admin.reportes.viveros.show') }}" class="ml-2 text-sm text-gray-600 hover:text-gray-900">Limpiar</a>
                    </div>
                </form>
            </div>

            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">

                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Previsualización del Reporte</h2>
                    <a href="{{ route('admin.reportes.viveros.download', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white rounded-lg text-xs font-semibold hover:shadow-lg transition">
                        📥 Descargar PDF
                    </a>
                </div>

                <div class="relative overflow-x-auto rounded-xl border border-[#e0e0e0]">
                    <table class="w-full text-sm">
                        <thead class="bg-gradient-to-r from-[#fafafa] to-[#f5f5f5] border-b border-[#e0e0e0]">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">🌱 Vivero</th>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">👤 Dueño</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">Total Módulos</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">Ocupados</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">Advertencias</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">Críticos</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">Offline</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e0e0e0]">
                            @forelse ($viveros as $vivero)
                            <tr class="hover:bg-[#ffdef0]/30 transition">
                                <td class="px-6 py-4 font-semibold text-[#1a1a1a]">{{ $vivero->nombre }}</td>
                                <td class="px-6 py-4 text-[#555555]">{{ $vivero->user->full_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 font-bold text-gray-700">{{ $vivero->modulos_total_count }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $vivero->modulos_ocupados_count > 0 ? 'bg-sky-100 text-sky-700' : 'bg-gray-100 text-gray-500' }} font-bold">{{ $vivero->modulos_ocupados_count }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $vivero->alertas_advertencia_count > 0 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500' }} font-bold">{{ $vivero->alertas_advertencia_count }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $vivero->alertas_critico_count > 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-500' }} font-bold">{{ $vivero->alertas_critico_count }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $vivero->modulos_offline_count > 0 ? 'bg-slate-200 text-slate-700' : 'bg-gray-100 text-gray-500' }} font-bold">{{ $vivero->modulos_offline_count }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-[#999999]">
                                    <p>No hay viveros registrados para mostrar.</p>
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