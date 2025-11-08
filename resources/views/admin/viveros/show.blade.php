<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            🌱 Detalles del Vivero: <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">{{ $vivero->nombre }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">

                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Información Completa</h2>
                    <a href="{{ route('admin.viveros.index') }}" class="text-[#555555] hover:text-[#9c0000] transition font-medium text-sm">← Volver a la lista</a>
                </div>

                <div class="text-[#555555] space-y-6 border-t border-[#e0e0e0] pt-6">
                    <div class="flex items-center justify-between p-4 bg-[#fafafa] rounded-lg">
                        <strong class="text-[#1a1a1a] font-semibold">🌱 Nombre:</strong>
                        <p class="text-lg font-medium text-[#1a1a1a]">{{ $vivero->nombre }}</p>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-[#fafafa] rounded-lg">
                        <strong class="text-[#1a1a1a] font-semibold">👤 Dueño:</strong>
                        <p class="text-lg font-medium text-[#1a1a1a]">{{ $vivero->user->full_name }}</p>
                    </div>
                    @if($vivero->descripcion)
                        <div class="p-4 bg-[#fafafa] rounded-lg">
                            <strong class="text-[#1a1a1a] font-semibold block mb-2">📝 Descripción:</strong>
                            <p class="text-lg text-[#555555]">{{ $vivero->descripcion }}</p>
                        </div>
                    @endif
                    @if($vivero->latitud && $vivero->longitud)
                        <div class="p-4 bg-[#fafafa] rounded-lg">
                            <strong class="text-[#1a1a1a] font-semibold block mb-2">📍 Coordenadas:</strong>
                            <p class="text-lg text-[#555555]">
                                {{ $vivero->latitud }}, {{ $vivero->longitud }}
                                <a href="https://www.google.com/maps?q={{ $vivero->latitud }},{{ $vivero->longitud }}" target="_blank" class="ml-2 text-[#ff4b65] hover:underline font-medium">(Ver en mapa)</a>
                            </p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>