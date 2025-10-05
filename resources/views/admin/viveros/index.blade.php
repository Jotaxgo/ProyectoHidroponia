<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Viveros') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                <h1 class="text-2xl font-bold mb-4">Lista de Viveros</h1>

                    <div class="mb-4">
                        <a href="{{ route('admin.viveros.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Crear Nuevo Vivero
                        </a>
                        <a href="{{ route('admin.viveros.trash') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            Ver Papelera 🗑️
                        </a>
                    </div>

                    <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-900 rounded-lg">
                        <form method="GET" action="{{ route('admin.viveros.index') }}">
                            <label for="filtro-dueño" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Filtrar por Dueño</label>
                            <div class="flex items-center mt-1 space-x-4">

                                <div class="flex-grow">
                                    <select id="filtro-dueño" name="dueño_id" style="visibility: hidden;" class="w-full">
                                        @foreach ($dueños as $dueño)
                                            <option value="{{ $dueño->id }}" @if(request('dueño_id') == $dueño->id) selected @endif>
                                                {{ $dueño->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex-shrink-0">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Filtrar
                                    </button>
                                    <a href="{{ route('admin.viveros.index') }}" class="text-gray-500 hover:text-gray-700 ml-2 py-2 px-4">
                                        Limpiar
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>


                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dueño</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                            @foreach ($viveros as $vivero)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $vivero->nombre }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $vivero->ubicacion }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $vivero->user->name ?? 'Sin asignar' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.viveros.modulos.index', $vivero) }}" class="text-green-600 hover:text-green-900 mr-4">
                                        Gestionar Módulos
                                    </a>
                                    <a href="{{ route('admin.viveros.edit', $vivero) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>

                                    <form action="{{ route('admin.viveros.destroy', $vivero) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-4"
                                                onclick="return confirm('¿Estás seguro de que quieres eliminar este vivero?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
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
