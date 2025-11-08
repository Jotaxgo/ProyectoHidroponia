<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            🌱 <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Crear Nuevo Vivero</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent mb-8">Detalles del Vivero</h2>

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

                <form method="POST" action="{{ route('admin.viveros.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="nombre" class="block font-semibold text-sm text-[#1a1a1a] mb-2">🌱 Nombre del Vivero</label>
                            <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" required autofocus class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                        </div>
                        
                        <div>
                            <label for="select-dueño" class="block font-semibold text-sm text-[#1a1a1a] mb-2">👤 Asignar Dueño</label>
                            <select name="user_id" id="select-dueño" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                                @forelse ($dueños as $dueño)
                                    <option value="{{ $dueño->id }}">{{ $dueño->full_name }}</option>
                                @empty
                                    <option value="" disabled>No hay "Dueños de Vivero" disponibles</option>
                                @endforelse
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-sm text-[#1a1a1a] mb-2">📍 Ubicación (Arrastra el marcador)</label>
                            <div id="map" style="height: 400px; width: 100%;" class="mt-1 rounded-lg z-0 border border-[#e0e0e0]"></div>
                        </div>

                        <input type="hidden" name="latitud" id="latitud">
                        <input type="hidden" name="longitud" id="longitud">

                        <div>
                            <label for="descripcion" class="block font-semibold text-sm text-[#1a1a1a] mb-2">📝 Descripción (opcional)</label>
                            <textarea id="descripcion" name="descripcion" rows="4" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">{{ old('descripcion') }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-[#e0e0e0]">
                        <a href="{{ route('admin.viveros.index') }}" class="text-[#555555] hover:text-[#9c0000] transition font-medium text-sm">← Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                            ✨ Crear Vivero
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
            var initialLat = -17.3935;
            var initialLng = -66.1570;
            var initialZoom = 12;
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
            
            if ('geolocation' in navigator) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var userLatLng = L.latLng(position.coords.latitude, position.coords.longitude);
                    map.setView(userLatLng, 12);
                    marker.setLatLng(userLatLng);
                    updateInputs(userLatLng);
                }, function(error) {
                    console.log("Error de geolocalización: ", error.message);
                    updateInputs(marker.getLatLng());
                });
            } else {
                console.log("Geolocalización no soportada.");
                updateInputs(marker.getLatLng());
            }
        });
    </script>
    @endpush
</x-app-layout>