<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Inventario General de Módulos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-900 rounded-lg">
                        <form method="GET" action="{{ route('admin.modulos.indexAll') }}">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                                <div>
                                    <label for="vivero_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Filtrar por Vivero</label>
                                    <select name="vivero_id" id="vivero_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                        <option value="">Todos los Viveros</option>
                                        @foreach ($viveros as $vivero)
                                            <option value="{{ $vivero->id }}" @if(request('vivero_id') == $vivero->id) selected @endif>
                                                {{ $vivero->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="estado" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Filtrar por Estado</label>
                                    <select name="estado" id="estado" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                        <option value="">Todos los Estados</option>
                                        <option value="Disponible" @if(request('estado') == 'Disponible') selected @endif>Disponible</option>
                                        <option value="Ocupado" @if(request('estado') == 'Ocupado') selected @endif>Ocupado</option>
                                        <option value="Mantenimiento" @if(request('estado') == 'Mantenimiento') selected @endif>Mantenimiento</option>
                                    </select>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <button type="submit" class="w-full justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Filtrar
                                    </button>
                                    <a href="{{ route('admin.modulos.indexAll') }}" class="w-full text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                        Limpiar
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vivero</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                            @forelse ($modulos as $modulo)
                            <tr>
                                <td class="px-6 py-4">{{ $modulo->codigo_identificador }}</td>
                                <td class="px-6 py-4">{{ $modulo->vivero->nombre }}</td>
                                <td class="px-6 py-4">{{ $modulo->estado }}</td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <a href="{{ route('admin.viveros.modulos.edit', [$modulo->vivero, $modulo]) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay módulos que coincidan con el filtro.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>