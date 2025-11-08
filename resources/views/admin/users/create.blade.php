<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            👤 <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Crear Nuevo Usuario</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" x-data="{ role_id: '{{ old('role_id', $roles->first()->id) }}' }" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent mb-8">Detalles del Usuario</h2>

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

                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="nombres" class="block font-semibold text-sm text-[#1a1a1a] mb-2">Nombres</label>
                            <input id="nombres" name="nombres" type="text" value="{{ old('nombres') }}" required autofocus class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                        </div>

                        <div>
                            <label for="primer_apellido" class="block font-semibold text-sm text-[#1a1a1a] mb-2">Primer Apellido</label>
                            <input id="primer_apellido" name="primer_apellido" type="text" value="{{ old('primer_apellido') }}" required class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                        </div>

                        <div>
                            <label for="segundo_apellido" class="block font-semibold text-sm text-[#1a1a1a] mb-2">Segundo Apellido (Opcional)</label>
                            <input id="segundo_apellido" name="segundo_apellido" type="text" value="{{ old('segundo_apellido') }}" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                        </div>

                        <div>
                            <label for="email" class="block font-semibold text-sm text-[#1a1a1a] mb-2">📧 Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                        </div>
                        
                        <div>
                            <label for="role_id" class="block font-semibold text-sm text-[#1a1a1a] mb-2">🎯 Rol</label>
                            <select name="role_id" id="role_id" x-model="role_id" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->nombre_rol }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div x-show="role_id == '{{ $roles->where('nombre_rol', 'Dueño de Vivero')->first()->id }}'" x-transition class="pt-6 border-t border-[#e0e0e0] space-y-6">
                            <h3 class="text-lg font-bold text-[#1a1a1a]">🌱 Información del Vivero Principal</h3>

                            <div>
                                <label for="vivero_nombre" class="block font-semibold text-sm text-[#1a1a1a] mb-2">Nombre del Vivero</label>
                                <input id="vivero_nombre" name="vivero_nombre" type="text" value="{{ old('vivero_nombre') }}" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                            </div>

                            <div>
                                <label class="block font-semibold text-sm text-[#1a1a1a] mb-2">📍 Ubicación (Arrastra el marcador)</label>
                                <div id="map" style="height: 400px; width: 100%;" class="mt-1 rounded-lg z-0 border border-[#e0e0e0]"></div>
                            </div>

                            <input type="hidden" name="latitud" id="latitud">
                            <input type="hidden" name="longitud" id="longitud">

                            <div>
                                <label for="vivero_descripcion" class="block font-semibold text-sm text-[#1a1a1a] mb-2">📝 Descripción (opcional)</label>
                                <textarea name="vivero_descripcion" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">{{ old('vivero_descripcion') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-[#e0e0e0]">
                        <a href="{{ route('admin.users.index') }}" class="text-[#555555] hover:text-[#9c0000] transition font-medium text-sm">← Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                            ✉️ Crear y Enviar Invitación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revisa si el elemento del mapa existe en la página
            if (document.getElementById('map')) {
                var initialLat = -17.3935; // Cochabamba
                var initialLng = -66.1570;
                var initialZoom = 12;

                var map = L.map('map').setView([initialLat, initialLng], initialZoom);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                var marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

                var latInput = document.querySelector("#latitud");
                var lngInput = document.querySelector("#longitud");

                function updateInputs() {
                    var latlng = marker.getLatLng();
                    latInput.value = latlng.lat;
                    lngInput.value = latlng.lng;
                }

                marker.on('dragend', updateInputs);
                updateInputs();

                // --- EL ARREGLO ---
                // Usamos un observador para detectar cuándo el mapa se hace visible
                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) {
                        // Usamos un pequeño retraso para asegurar que la animación de Alpine.js termine
                        setTimeout(function () {
                            map.invalidateSize();
                        }, 100);
                    }
                }, { threshold: 0.1 });

                observer.observe(document.getElementById('map'));
            }
        });
    </script>
    @endpush
</x-app-layout>