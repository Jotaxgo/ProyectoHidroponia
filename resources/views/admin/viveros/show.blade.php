<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Detalles del Vivero: <span class="text-hydro-accent-gold">{{ $vivero->nombre }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-white">Información Completa</h2>
                    <a href="{{ route('admin.viveros.index') }}" class="text-gray-400 hover:text-white">&larr; Volver a la lista</a>
                </div>

                <div class="text-hydro-text-light space-y-4">
                    <div>
                        <strong class="text-gray-400">Nombre:</strong>
                        <p class="text-lg">{{ $vivero->nombre }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-400">Dueño:</strong>
                        <p class="text-lg">{{ $vivero->user->full_name }}</p>
                    </div>
                    @if($vivero->descripcion)
                        <div>
                            <strong class="text-gray-400">Descripción:</strong>
                            <p class="text-lg">{{ $vivero->descripcion }}</p>
                        </div>
                    @endif
                    @if($vivero->latitud && $vivero->longitud)
                        <div>
                            <strong class="text-gray-400">Coordenadas:</strong>
                            <p class="text-lg">
                                {{ $vivero->latitud }}, {{ $vivero->longitud }}
                                <a href="https://www.google.com/maps?q={{ $vivero->latitud }},{{ $vivero->longitud }}" target="_blank" class="ml-2 text-blue-400 hover:underline">(Ver en mapa)</a>
                            </p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>