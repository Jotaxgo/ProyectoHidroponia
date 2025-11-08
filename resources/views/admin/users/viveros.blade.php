<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Viveros de: <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">{{ $user->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Lista de Viveros Asignados</h2>
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 text-[#555555] hover:text-[#9c0000] transition font-medium text-sm">
                        ← Volver
                    </a>
                </div>

                <div class="relative overflow-x-auto rounded-xl border border-[#e0e0e0]">
                    <table class="w-full text-sm">
                        <thead class="bg-gradient-to-r from-[#fafafa] to-[#f5f5f5] border-b border-[#e0e0e0]">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Nombre del Vivero</th>
                                <th scope="col" class="px-6 py-4 text-left font-semibold text-[#1a1a1a]">Ubicación</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">Módulos</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-[#1a1a1a]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e0e0e0]">
                            @forelse ($viveros as $vivero)
                            <tr class="hover:bg-[#ffdef0]/30 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg">🌱</span>
                                        <span class="font-semibold text-[#1a1a1a]">{{ $vivero->nombre }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[#555555]">{{ $vivero->ubicacion }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold text-[#ff4b65]">{{ $vivero->modulos_count }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center">
                                        <a href="{{ route('admin.viveros.edit', $vivero) }}" class="inline-flex items-center px-3 py-1.5 bg-[#ffdef0] text-[#9c0000] rounded-lg text-xs font-semibold hover:bg-[#ffcce0] transition">
                                            ✏️ Editar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-[#999999]">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-2xl">📭</span>
                                        <p>Este usuario no tiene viveros asignados.</p>
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