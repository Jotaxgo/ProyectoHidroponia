<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Papelera de Viveros
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-white">Viveros Eliminados</h2>
                    <a href="{{ route('admin.viveros.index') }}" class="text-gray-400 hover:text-white">&larr; Volver a la lista</a>
                </div>

                <div class="relative overflow-x-auto rounded-lg">
                    <table class="w-full text-sm text-left text-hydro-text-light">
                        <thead class="text-xs text-white uppercase bg-hydro-accent-bright/80">
                            <tr>
                                <th scope="col" class="px-6 py-4">Nombre</th>
                                <th scope="col" class="px-6 py-4">Ubicación</th>
                                <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trashedViveros as $vivero)
                            <tr class="border-b border-hydro-dark">
                                <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">{{ $vivero->nombre }}</th>
                                <td class="px-6 py-4">{{ $vivero->ubicacion }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <form action="{{ route('admin.viveros.restore', $vivero->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-hydro-accent-light/20 text-hydro-accent-light rounded-md text-xs hover:bg-hydro-accent-light/40 transition">
                                                Restaurar
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.viveros.forceDelete', $vivero->id) }}" method="POST" class="inline" onsubmit="return confirm('¿ELIMINACIÓN PERMANENTE? Esta acción no se puede deshacer.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-red-500/20 text-red-300 rounded-md text-xs hover:bg-red-500/40 transition">
                                                Borrar para Siempre
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="border-b border-hydro-dark">
                                <td colspan="3" class="px-6 py-4 text-center">La papelera está vacía.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>