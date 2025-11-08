<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Gestión de Usuarios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">

                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Lista de Usuarios</h2>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.users.trash') }}" class="inline-flex items-center px-4 py-2 text-[#555555] hover:text-[#9c0000] transition font-medium text-sm">
                            🗑️ Papelera
                        </a>
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                            + Nuevo Usuario
                        </a>
                    </div>
                </div>

                <div class="relative overflow-x-auto rounded-xl border border-[#e0e0e0]">
                    <table class="w-full text-sm">
                        <thead class="bg-gradient-to-r from-[#fafafa] to-[#f5f5f5] border-b border-[#e0e0e0]">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Nombre</th>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Email</th>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Rol</th>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Estado</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">Viveros</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e0e0e0]">
                            @forelse ($users as $user)
                            <tr class="hover:bg-[#ffdef0]/30 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#ff4b65] to-[#9c0000] flex items-center justify-center text-white text-xs font-bold">
                                            {{ substr($user->full_name ?? 'U', 0, 1) }}
                                        </div>
                                        <span class="font-medium text-[#1a1a1a]">{{ $user->full_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[#555555]">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#96d900]/15 text-[#6b9b00]">
                                        {{ $user->role->nombre_rol }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $user->estado == 'Activo' ? 'bg-[#96d900]/15 text-[#6b9b00]' : 'bg-red-100 text-red-700' }}">
                                        {{ $user->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($user->viveros_count > 0)
                                        <a href="{{ route('admin.users.showViveros', $user) }}" class="font-bold text-[#ff4b65] hover:text-[#9c0000] transition">
                                            {{ $user->viveros_count }}
                                        </a>
                                    @else
                                        <span class="text-[#999999]">0</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-3 py-1.5 bg-[#ffdef0] text-[#9c0000] rounded-lg text-xs font-semibold hover:bg-[#ffcce0] transition">
                                            ✏️ Editar
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200 transition">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-[#999999]">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-2xl">📭</span>
                                        <p>No hay usuarios registrados.</p>
                                    </div>
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