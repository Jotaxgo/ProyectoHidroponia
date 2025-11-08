<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            ✏️ <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Editar Vivero</span>
        </h2>
        
        @if (session('info'))
            <div class="bg-blue-500/20 text-blue-700 p-4 rounded-lg mt-4 border border-blue-500/40">
                ℹ️ {{ session('info') }}
            </div>
        @endif
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent mb-8">Editando: 🌱 {{ $vivero->nombre }}</h2>

                @if ($errors->any())
                    <div class="bg-red-500/20 border border-red-500/40 text-red-700 p-4 rounded-lg mb-6">
                        <strong class="font-bold">⚠️ Error de Validación!</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.viveros.update', $vivero) }}">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <label for="nombre" class="block font-semibold text-sm text-[#1a1a1a] mb-2">🌱 Nombre del Vivero</label>
                            <input id="nombre" name="nombre" type="text" value="{{ old('nombre', $vivero->nombre) }}" required autofocus class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                        </div>
                        
                        <div>
                            <label for="select-dueño" class="block font-semibold text-sm text-[#1a1a1a] mb-2">👤 Asignar Dueño</label>
                            <select name="user_id" id="select-dueño" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                                @foreach ($dueños as $dueño)
                                    <option value="{{ $dueño->id }}" @if($vivero->user_id == $dueño->id) selected @endif>
                                        {{ $dueño->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-sm text-[#1a1a1a] mb-2">📍 Ubicación (Arrastra el marcador)</label>
                            <div id="map" style="height: 400px; width: 100%;" class="mt-1 rounded-lg z-0 border border-[#e0e0e0]"></div>
                        </div>

                        <input type="hidden" name="latitud" id="latitud" value="{{ old('latitud', $vivero->latitud) }}">
                        <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud', $vivero->longitud) }}">

                        <div>
                            <label for="descripcion" class="block font-semibold text-sm text-[#1a1a1a] mb-2">📝 Descripción (opcional)</label>
                            <textarea id="descripcion" name="descripcion" rows="4" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">{{ old('descripcion', $vivero->descripcion) }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-[#e0e0e0]">
                        <a href="{{ route('admin.viveros.index') }}" class="text-[#555555] hover:text-[#9c0000] transition font-medium text-sm">← Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                            💾 Actualizar Vivero
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- INICIALIZACIÓN DE SELECT2 ---
            $('#select-dueño').select2();

            // --- INICIALIZACIÓN DEL MAPA ---
            // Usamos las coordenadas guardadas del vivero, o las de Cochabamba si no existen.
            var initialLat = {{ old('latitud', $vivero->latitud) ?? -17.3935 }};
            var initialLng = {{ old('longitud', $vivero->longitud) ?? -66.1570 }};
            var initialZoom = {{ ($vivero->latitud && $vivero->longitud) ? 15 : 12 }};

            var map = L.map('map').setView([initialLat, initialLng], initialZoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

            var latInput = document.querySelector("#latitud");
            var lngInput = document.querySelector("#longitud");

            function updateInputs(latlng) {
                latInput.value = latlng.lat;
                lngInput.value = latlng.lng;
            }
            
            marker.on('dragend', function() { updateInputs(marker.getLatLng()); });
        });
    </script>
    @endpush
</x-app-layout>