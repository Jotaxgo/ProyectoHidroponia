<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Viveros de: <span class="text-hydro-accent-gold">{{ $user->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-white">Lista de Viveros Asignados</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-white">&larr; Volver a la lista de usuarios</a>
                </div>

                <div class="relative overflow-x-auto rounded-lg">
                    <table class="w-full text-sm text-left text-hydro-text-light">
                        <thead class="text-xs text-white uppercase bg-hydro-accent-bright/80">
                            <tr>
                                <th scope="col" class="px-6 py-4">Nombre del Vivero</th>
                                <th scope="col" class="px-6 py-4">Ubicación</th>
                                <th scope="col" class="px-6 py-4 text-center">Módulos</th>
                                <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($viveros as $vivero)
                            <tr class="border-b border-hydro-dark hover:bg-hydro-dark/50">
                                <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">{{ $vivero->nombre }}</th>
                                <td class="px-6 py-4">{{ $vivero->ubicacion }}</td>
                                <td class="px-6 py-4 text-center">{{ $vivero->modulos_count }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.viveros.edit', $vivero) }}" class="inline-flex items-center px-2.5 py-1.5 bg-hydro-accent-gold/20 text-hydro-accent-gold rounded-md text-xs hover:bg-hydro-accent-gold/40 transition">
                                        Editar Vivero
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr class="border-b border-hydro-dark">
                                <td colspan="4" class="px-6 py-4 text-center">Este usuario no tiene viveros asignados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>