<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Gestión de Usuarios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-white">Lista de Usuarios</h2>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.users.trash') }}" class="text-gray-400 hover:text-white">Ver Papelera 🗑️</a>
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">
                            Crear Nuevo Usuario
                        </a>
                    </div>
                </div>

                <div class="relative overflow-x-auto rounded-lg">
                    <table class="w-full text-sm text-left text-hydro-text-light">
                        <thead class="text-xs text-white uppercase bg-hydro-accent-bright/80">
                            <tr>
                                <th scope="col" class="px-6 py-4">Nombre</th>
                                <th scope="col" class="px-6 py-4">Email</th>
                                <th scope="col" class="px-6 py-4">Rol</th>
                                <th scope="col" class="px-6 py-4">Estado</th>
                                <th scope="col" class="px-6 py-4 text-center">Nº Viveros</th>
                                <th scope="col" class="px-6 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                            <tr class="border-b border-hydro-dark hover:bg-hydro-dark/50">
                                <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">
                                    {{ $user->name }}
                                </th>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">{{ $user->role->nombre_rol }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $user->estado == 'Activo' ? 'bg-hydro-accent-light/20 text-hydro-accent-light' : 'bg-red-500/20 text-red-300' }}">
                                        {{ $user->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($user->viveros_count > 0)
                                        <a href="{{ route('admin.users.showViveros', $user) }}" class="font-bold text-hydro-accent-gold hover:underline">
                                            {{ $user->viveros_count }}
                                        </a>
                                    @else
                                        0
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-2.5 py-1.5 bg-hydro-accent-gold/20 text-hydro-accent-gold rounded-md text-xs hover:bg-hydro-accent-gold/40 transition">
                                            Editar
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-red-500/20 text-red-300 rounded-md text-xs hover:bg-red-500/40 transition">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="border-b border-hydro-dark">
                                <td colspan="6" class="px-6 py-4 text-center">No hay usuarios registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>