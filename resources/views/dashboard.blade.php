<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Dashboard del Administrador
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-hydro-card p-6 rounded-lg shadow-xl">
                    <div class="flex items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Total de Usuarios</p>
                            <p class="text-3xl font-bold text-white">{{ $stats['total_users'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-hydro-card p-6 rounded-lg shadow-xl">
                    <div class="flex items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Total de Viveros</p>
                            <p class="text-3xl font-bold text-white">{{ $stats['total_viveros'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-hydro-card p-6 rounded-lg shadow-xl">
                    <div class="flex items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Total de Módulos</p>
                            <p class="text-3xl font-bold text-white">{{ $stats['total_modulos'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-hydro-card p-6 rounded-lg shadow-xl">
                    <div class="flex items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Módulos Ocupados</p>
                            <p class="text-3xl font-bold text-yellow-400">{{ $stats['modulos_ocupados'] }}</p>
                        </div>
                    </div>
                </div>

            </div>
            </div>
    </div>
</x-app-layout>
