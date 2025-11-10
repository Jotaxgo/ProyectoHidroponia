<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Gestión de Viveros
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">

                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Lista de Viveros</h2>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.viveros.trash') }}" class="inline-flex items-center px-4 py-2 text-[#555555] hover:text-[#9c0000] transition font-medium text-sm">
                            🗑️ Papelera
                        </a>
                        <a href="{{ route('admin.viveros.create') }}" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                            + Nuevo Vivero
                        </a>
                    </div>
                </div>

                <div class="mb-6 p-5 bg-white/50 rounded-xl border" style="border-color: var(--border);">
                    <form method="GET" action="{{ route('admin.viveros.index') }}">
                        <label for="filtro-dueño" class="block font-semibold text-sm text-text-dark mb-3">🔍 Filtrar por Dueño</label>
                        <div class="flex items-center gap-3">
                            <div class="flex-grow">
                                <select id="filtro-dueño" name="dueño_id" class="block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm focus:border-strawberry focus:ring-strawberry">
                                    <option value="">Todos los Dueños</option>
                                    @foreach ($dueños as $dueño)
                                        <option value="{{ $dueño->id }}" @if(request('dueño_id') == $dueño->id) selected @endif>
                                            {{ $dueño->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-sm text-white transition" style="background-color: var(--strawberry);" onmouseover="this.style.backgroundColor='var(--strawberry-dark)'" onmouseout="this.style.backgroundColor='var(--strawberry)'">
                                    Filtrar
                                </button>
                                <a href="{{ route('admin.viveros.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-lg font-semibold text-sm text-gray-800 transition hover:bg-gray-300">
                                    Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="relative overflow-x-auto rounded-xl border border-[#e0e0e0]">
                    <table class="w-full text-sm">
                        <thead class="bg-gradient-to-r from-[#fafafa] to-[#f5f5f5] border-b border-[#e0e0e0]">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Nombre</th>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Dueño</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">Módulos</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e0e0e0]">
                            @forelse ($viveros as $vivero)
                            <tr class="hover:bg-[#ffdef0]/30 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#96d900] to-[#6b9b00] flex items-center justify-center text-white text-xs font-bold">
                                            🌱
                                        </div>
                                        <span class="font-semibold text-[#1a1a1a]">{{ $vivero->nombre }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[#555555]">{{ $vivero->user->full_name ?? 'Sin asignar' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.viveros.modulos.index', $vivero) }}" class="font-bold text-[#ff4b65] hover:text-[#9c0000] transition">
                                        {{ $vivero->modulos_count }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.viveros.show', $vivero) }}" class="inline-flex items-center px-3 py-1.5 bg-[#f0f0f0] text-[#555555] rounded-lg text-xs font-semibold hover:bg-[#e0e0e0] transition">
                                            👁️ Ver
                                        </a>
                                        <a href="{{ route('admin.viveros.modulos.index', $vivero) }}" class="inline-flex items-center px-3 py-1.5 bg-[#e8f5e9] text-[#6b9b00] rounded-lg text-xs font-semibold hover:bg-[#d4edda] transition">
                                            📦 Módulos
                                        </a>
                                        <a href="{{ route('admin.viveros.edit', $vivero) }}" class="inline-flex items-center px-3 py-1.5 bg-[#ffdef0] text-[#9c0000] rounded-lg text-xs font-semibold hover:bg-[#ffcce0] transition">
                                            ✏️ Editar
                                        </a>
                                        <form action="{{ route('admin.viveros.destroy', $vivero) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200 transition">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-[#999999]">
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