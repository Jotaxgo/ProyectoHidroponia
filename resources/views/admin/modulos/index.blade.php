<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Gestionar Módulos del Vivero: <span class="text-hydro-accent-gold">{{ $vivero->nombre }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- <div class="mb-4">
                    <a href="{{ route('admin.viveros.index') }}" class="text-gray-400 hover:text-white">&larr; Volver a los viveros</a>    
                </div> -->
                <!-- <a href="{{ route('admin.viveros.index') }}" class="text-indigo-600 hover:text-indigo-900">
                    &larr; Volver a Viveros
                </a> -->

                @if(Auth::user()->role->nombre_rol == 'Admin')
                    <a href="{{ route('admin.viveros.index') }}" class="text-gray-400 hover:text-white">
                        &larr; Volver a la Lista de Viveros
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white">
                        &larr; Volver a mi Dashboard
                    </a>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-white">Lista de Módulos</h2>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.viveros.modulos.trash', $vivero) }}" class="text-gray-400 hover:text-white">Ver Papelera 🗑️</a>
                        <a href="{{ route('admin.viveros.modulos.create', $vivero) }}" class="inline-flex items-center px-4 py-2 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">
                            Añadir Nuevo Módulo
                        </a>
                    </div>
                </div>

                <div class="relative overflow-x-auto rounded-lg">
                    <table class="w-full text-sm text-left text-hydro-text-light">
                        <thead class="text-xs text-white uppercase bg-hydro-accent-bright/80">
                            <tr>
                                <th scope="col" class="px-6 py-4">Código</th>
                                <th scope="col" class="px-6 py-4">Device ID</th>
                                <th scope="col" class="px-6 py-4">Estado</th>
                                <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($modulos as $modulo)
                            <tr class="border-b border-hydro-dark hover:bg-hydro-dark/50">
                                <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">{{ $modulo->codigo_identificador }}</th>
                                <td class="px-6 py-4">{{ $modulo->hardware_info['device_id'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($modulo->estado == 'Disponible') bg-green-500/20 text-green-300
                                        @elseif($modulo->estado == 'Ocupado') bg-yellow-500/20 text-yellow-300
                                        @else bg-red-500/20 text-red-300 @endif">
                                        {{ $modulo->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.viveros.modulos.edit', [$vivero, $modulo]) }}" class="inline-flex items-center px-2.5 py-1.5 bg-hydro-accent-gold/20 text-hydro-accent-gold rounded-md text-xs hover:bg-hydro-accent-gold/40 transition">Editar</a>
                                        <form action="{{ route('admin.viveros.modulos.destroy', [$vivero, $modulo]) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-red-500/20 text-red-300 rounded-md text-xs hover:bg-red-500/40 transition">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="border-b border-hydro-dark">
                                <td colspan="4" class="px-6 py-4 text-center">Este vivero aún no tiene módulos.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>