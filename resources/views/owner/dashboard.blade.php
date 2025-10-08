<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Dashboard del Propietario
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold text-white mb-6">Mis Viveros</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($viveros as $vivero)
                        <div class="bg-hydro-dark rounded-lg p-4 flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-lg text-hydro-accent-gold">{{ $vivero->nombre }}</h3>
                                <p class="text-sm text-gray-400">{{ $vivero->ubicacion }}</p>
                                <p class="text-sm text-gray-400 mt-2">
                                    <span class="font-bold text-white">{{ $vivero->modulos_count }}</span> Módulos
                                </p>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('admin.viveros.modulos.index', $vivero) }}" class="inline-block w-full text-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 transition">
                                    Gestionar Módulos
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 col-span-full">Aún no tienes viveros asignados. Contacta a un administrador.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</x-app-layout>