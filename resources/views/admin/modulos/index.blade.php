<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Gestionar Módulos del Vivero: <span class="text-indigo-500">{{ $vivero->nombre }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-4">
                        <a href="{{ route('admin.viveros.index', $vivero) }}" class="text-indigo-600 hover:text-indigo-900">
                            &larr; Volver a la lista de viveros
                        </a>
                    </div>

                    <div class="mb-4 flex justify-between items-center">
                        <a href="{{ route('admin.viveros.modulos.create', $vivero) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Añadir Nuevo Módulo
                        </a>
                        <a href="{{ route('admin.viveros.modulos.trash', $vivero) }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            Ver Papelera 🗑️
                        </a>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo Sistema</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                            @forelse ($modulos as $modulo)
                            <tr>
                                <td class="px-6 py-4">{{ $modulo->codigo_identificador }}</td>
                                <td class="px-6 py-4">{{ $modulo->tipo_sistema }}</td>
                                <td class="px-6 py-4">{{ $modulo->estado }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.viveros.modulos.edit', [$vivero, $modulo]) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>

                                    <form action="{{ route('admin.viveros.modulos.destroy', [$vivero, $modulo]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-4"
                                                onclick="return confirm('¿Estás seguro de que quieres eliminar este módulo?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    Este vivero aún no tiene módulos.
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