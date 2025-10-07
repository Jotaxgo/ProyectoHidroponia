<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Vista General de Módulos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold text-white mb-6">Todos los Módulos</h2>

                <div class="mb-4 p-4 bg-hydro-dark rounded-lg">
                    <form method="GET" action="{{ route('admin.modulos.indexAll') }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label for="vivero_id" class="block font-medium text-sm text-hydro-text-light">Filtrar por Vivero</label>
                                <select name="vivero_id" id="vivero_id" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                                    <option value="">Todos los Viveros</option>
                                    @foreach ($viveros as $vivero)
                                        <option value="{{ $vivero->id }}" @if(request('vivero_id') == $vivero->id) selected @endif>{{ $vivero->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="estado" class="block font-medium text-sm text-hydro-text-light">Filtrar por Estado</label>
                                <select name="estado" id="estado" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                                    <option value="">Todos los Estados</option>
                                    <option value="Disponible" @if(request('estado') == 'Disponible') selected @endif>Disponible</option>
                                    <option value="Ocupado" @if(request('estado') == 'Ocupado') selected @endif>Ocupado</option>
                                    <option value="Mantenimiento" @if(request('estado') == 'Mantenimiento') selected @endif>Mantenimiento</option>
                                </select>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="submit" class="w-full justify-center bg-hydro-accent-gold hover:opacity-90 text-hydro-dark font-bold py-2 px-4 rounded transition">Filtrar</button>
                                <a href="{{ route('admin.modulos.indexAll') }}" class="w-full text-center bg-gray-600 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded transition">Limpiar</a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="relative overflow-x-auto rounded-lg">
                    <table class="w-full text-sm text-left text-hydro-text-light">
                        <thead class="text-xs text-white uppercase bg-hydro-accent-bright/80">
                            <tr>
                                <th scope="col" class="px-6 py-4">Código</th>
                                <th scope="col" class="px-6 py-4">Vivero</th>
                                <th scope="col" class="px-6 py-4">Estado</th>
                                <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($modulos as $modulo)
                            <tr class="border-b border-hydro-dark hover:bg-hydro-dark/50">
                                <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">{{ $modulo->codigo_identificador }}</th>
                                <td class="px-6 py-4">{{ $modulo->vivero->nombre }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($modulo->estado == 'Disponible') bg-green-500/20 text-green-300
                                        @elseif($modulo->estado == 'Ocupado') bg-yellow-500/20 text-yellow-300
                                        @else bg-red-500/20 text-red-300 @endif">
                                        {{ $modulo->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.viveros.modulos.edit', [$modulo->vivero, $modulo]) }}" class="inline-flex items-center px-2.5 py-1.5 bg-hydro-accent-gold/20 text-hydro-accent-gold rounded-md text-xs hover:bg-hydro-accent-gold/40 transition">Editar</a>
                                </td>
                            </tr>
                            @empty
                            <tr class="border-b border-hydro-dark">
                                <td colspan="4" class="px-6 py-4 text-center">No hay módulos que coincidan con el filtro.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>