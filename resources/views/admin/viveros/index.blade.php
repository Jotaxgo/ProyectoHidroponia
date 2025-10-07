<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-star-light leading-tight">
            Gestión de Viveros
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-white">Lista de Viveros</h2>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.viveros.trash') }}" class="text-gray-400 hover:text-white">Ver Papelera 🗑️</a>
                        <a href="{{ route('admin.viveros.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-500 transition">
                            Crear Nuevo Vivero
                        </a>
                    </div>
                </div>

                <div class="mb-4 p-4 bg-gray-900 rounded-lg">
                    <form method="GET" action="{{ route('admin.viveros.index') }}">
                        <label for="filtro-dueño" class="block font-medium text-sm text-gray-300">Filtrar por Dueño</label>
                        <div class="flex items-center mt-1 space-x-4">
                            <div class="flex-grow">
                                <select id="filtro-dueño" name="dueño_id" style="visibility: hidden;">
                                    <option value="">Todos los Dueños</option>
                                    @foreach ($dueños as $dueño)
                                        <option value="{{ $dueño->id }}" @if(request('dueño_id') == $dueño->id) selected @endif>
                                            {{ $dueño->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded">Filtrar</button>
                                <a href="{{ route('admin.viveros.index') }}" class="text-gray-400 hover:text-white ml-2 py-2 px-4">Limpiar</a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="relative overflow-x-auto rounded-lg">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-900">
                            <tr>
                                <th scope="col" class="px-6 py-4">Nombre</th>
                                <th scope="col" class="px-6 py-4">Ubicación</th>
                                <th scope="col" class="px-6 py-4">Dueño</th>
                                <th scope="col" class="px-6 py-4 text-center">Módulos</th>
                                <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($viveros as $vivero)
                            <tr class="bg-gray-800 border-b border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">{{ $vivero->nombre }}</th>
                                <td class="px-6 py-4">{{ $vivero->ubicacion }}</td>
                                <td class="px-6 py-4">{{ $vivero->user->name ?? 'Sin asignar' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.viveros.modulos.index', $vivero) }}" class="font-medium text-blue-400 hover:underline">{{ $vivero->modulos_count }}</a>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.viveros.modulos.index', $vivero) }}" class="inline-flex items-center px-2.5 py-1.5 bg-gray-500/20 text-gray-300 rounded-md text-xs hover:bg-gray-500/40 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                                            Módulos
                                        </a>
                                        <a href="{{ route('admin.viveros.edit', $vivero) }}" class="inline-flex items-center px-2.5 py-1.5 bg-blue-500/20 text-blue-300 rounded-md text-xs hover:bg-blue-500/40 transition">
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
                            <tr class="bg-gray-800 border-b border-gray-700">
                                <td colspan="5" class="px-6 py-4 text-center">No hay viveros registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#filtro-dueño',{
                onInitialize: function() {
                    this.wrapper.style.visibility = 'visible';
                }
            });
        });
    </script>
    @endpush
</x-app-layout>