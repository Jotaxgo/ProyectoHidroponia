<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Vista General de Módulos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent mb-8">Todos los Módulos</h2>

                <div class="mb-6 p-5 bg-[#fafafa] rounded-xl border border-[#e0e0e0]">
                    <form method="GET" action="{{ route('admin.modulos.indexAll') }}">
                        <label class="block font-semibold text-sm text-[#1a1a1a] mb-4">🔍 Filtros</label>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                            <div>
                                <label for="vivero_id" class="block font-medium text-xs text-[#555555] mb-2">Vivero</label>
                                <select name="vivero_id" id="vivero_id" class="block w-full px-3 py-2 bg-white border border-[#e0e0e0] text-[#1a1a1a] rounded-lg focus:outline-none focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] text-sm">
                                    <option value="">Todos los Viveros</option>
                                    @foreach ($viveros as $vivero)
                                        <option value="{{ $vivero->id }}" @if(request('vivero_id') == $vivero->id) selected @endif>{{ $vivero->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="estado" class="block font-medium text-xs text-[#555555] mb-2">Estado</label>
                                <select name="estado" id="estado" class="block w-full px-3 py-2 bg-white border border-[#e0e0e0] text-[#1a1a1a] rounded-lg focus:outline-none focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] text-sm">
                                    <option value="">Todos los Estados</option>
                                    <option value="Disponible" @if(request('estado') == 'Disponible') selected @endif>✅ Disponible</option>
                                    <option value="Ocupado" @if(request('estado') == 'Ocupado') selected @endif>🌱 Ocupado</option>
                                    <option value="Mantenimiento" @if(request('estado') == 'Mantenimiento') selected @endif>🔧 Mantenimiento</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit" class="px-5 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white font-semibold rounded-lg hover:shadow-lg transition text-sm">
                                    Filtrar
                                </button>
                                <a href="{{ route('admin.modulos.indexAll') }}" class="px-5 py-2 bg-[#e0e0e0] text-[#555555] font-semibold rounded-lg hover:bg-[#d0d0d0] transition text-sm">
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
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Código</th>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Vivero</th>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Estado</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e0e0e0]">
                            @forelse ($modulos as $modulo)
                            <tr class="hover:bg-[#ffdef0]/30 transition">
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-[#1a1a1a]">{{ $modulo->codigo_identificador }}</span>
                                </td>
                                <td class="px-6 py-4 text-[#555555]">{{ $modulo->vivero->nombre }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        @if($modulo->estado == 'Disponible') bg-[#96d900]/15 text-[#6b9b00]
                                        @elseif($modulo->estado == 'Ocupado') bg-yellow-100 text-yellow-700
                                        @else bg-red-100 text-red-700 @endif">
                                        @if($modulo->estado == 'Disponible') ✅ 
                                        @elseif($modulo->estado == 'Ocupado') 🌱 
                                        @else 🔧 @endif
                                        {{ $modulo->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($modulo->estado == 'Disponible')
                                            <a href="{{ route('admin.viveros.modulos.startCultivoForm', [$modulo->vivero, $modulo]) }}" class="inline-flex items-center px-3 py-1.5 bg-[#96d900]/15 text-[#6b9b00] rounded-lg text-xs font-semibold hover:bg-[#96d900]/25 transition">
                                                🌱 Iniciar
                                            </a>
                                        @endif

                                        <a href="{{ route('admin.viveros.modulos.edit', [$modulo->vivero, $modulo]) }}" class="inline-flex items-center px-3 py-1.5 bg-[#ffdef0] text-[#9c0000] rounded-lg text-xs font-semibold hover:bg-[#ffcce0] transition">
                                            ✏️ Editar
                                        </a>

                                        <form action="{{ route('admin.viveros.modulos.destroy', [$modulo->vivero, $modulo]) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')" style="display: inline;">
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
                                        <p>No hay módulos que coincidan con el filtro.</p>
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