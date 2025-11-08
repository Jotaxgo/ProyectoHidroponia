<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Gestión de Viveros
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-white">Lista de Viveros</h2>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.viveros.trash') }}" class="text-gray-400 hover:text-white">Ver Papelera 🗑️</a>
                        <a href="{{ route('admin.viveros.create') }}" class="inline-flex items-center px-4 py-2 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">
                            Crear Nuevo Vivero
                        </a>
                    </div>
                </div>


                <div class="mb-4 p-4 bg-hydro-dark rounded-lg">
                    <form method="GET" action="{{ route('admin.viveros.index') }}">
                        <label for="filtro-dueño" class="block font-medium text-sm text-hydro-text-light mb-1">Filtrar por Dueño</label>
                        <div class="flex items-center space-x-4">
                            <div class="flex-grow">
                                <select id="filtro-dueño" name="dueño_id" class="block w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                                    <option value="">Todos los Dueños</option>
                                    @foreach ($dueños as $dueño)
                                        <option value="{{ $dueño->id }}" @if(request('dueño_id') == $dueño->id) selected @endif>
                                            {{ $dueño->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-shrink-0 flex items-center space-x-2">
                                <button type="submit" class="w-full justify-center bg-hydro-accent-gold hover:opacity-90 text-hydro-dark font-bold py-2 px-4 rounded transition">
                                    Filtrar
                                </button>
                                <a href="{{ route('admin.viveros.index') }}" class="w-full text-center bg-gray-600 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded transition">
                                    Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-700">
                    <table class="w-full text-sm text-left text-hydro-text-light">
                        <thead class="text-xs text-white uppercase bg-hydro-accent-bright/80">
                            <tr>
                                <th scope="col" class="px-6 py-4">Nombre</th>
                                <th scope="col" class="px-6 py-4">Dueño</th>
                                <th scope="col" class="px-6 py-4 text-center">Módulos</th>
                                <th scope="col" class="px-6 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($viveros as $vivero)
                            <tr class="border-b border-hydro-dark hover:bg-hydro-dark/50">
                                <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">{{ $vivero->nombre }}</th>
                                <!-- <td class="px-6 py-4">{{ $vivero->ubicacion }}</td> -->
                                <td class="px-6 py-4">{{ $vivero->user->full_name ?? 'Sin asignar' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.viveros.modulos.index', $vivero) }}" class="font-bold text-hydro-accent-gold hover:underline">
                                        {{ $vivero->modulos_count }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.viveros.show', $vivero) }}" class="inline-flex items-center px-2.5 py-1.5 bg-gray-500/20 text-gray-300 rounded-md text-xs hover:bg-gray-500/40 transition">
                                            Ver Detalles
                                        </a>
                                        <a href="{{ route('admin.viveros.modulos.index', $vivero) }}" class="inline-flex items-center px-2.5 py-1.5 bg-hydro-accent-light/20 text-hydro-accent-light rounded-md text-xs hover:bg-hydro-accent-light/40 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                                            Módulos
                                        </a>
                                        <a href="{{ route('admin.viveros.edit', $vivero) }}" class="inline-flex items-center px-2.5 py-1.5 bg-hydro-accent-gold/20 text-hydro-accent-gold rounded-md text-xs hover:bg-hydro-accent-gold/40 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                            Editar
                                        </a>
                                        <form action="{{ route('admin.viveros.destroy', $vivero) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-red-500/20 text-red-300 rounded-md text-xs hover:bg-red-500/40 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" /></svg>
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                    </td>
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