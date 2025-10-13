<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Crear Nuevo Vivero
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold text-white mb-6">Detalles del Vivero</h2>

                @if ($errors->any())
                    <div class="bg-red-500/20 text-red-300 p-4 rounded mb-6">
                        <strong class="font-bold">¡Error de Validación!</strong>
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
                            <label for="nombre" class="block font-medium text-sm text-hydro-text-light">Nombre del Vivero</label>
                            <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" required autofocus class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        </div>
                        
                        <div>
                            <label for="user_id" id="select-dueño" class="block font-medium text-sm text-hydro-text-light">Asignar Dueño</label>
                            <select name="user_id" id="user_id" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                                @forelse ($dueños as $dueño)
                                    <option value="{{ $dueño->id }}">{{ $dueño->name }}</option>
                                @empty
                                    <option value="" disabled>No hay "Dueños de Vivero" disponibles para asignar</option>
                                @endforelse
                            </select>
                        </div>

                        <!-- <div>
                            <label for="ubicacion" class="block font-medium text-sm text-hydro-text-light">Ubicación</label>
                            <input id="ubicacion" name="ubicacion" type="text" value="{{ old('ubicacion') }}" required class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        </div> -->
                        <div>
                            <label class="block font-medium text-sm text-hydro-text-light">Ubicación (Arrastra el marcador)</label>
                            <div id="map" style="height: 400px; width: 100%;" class="mt-1 rounded-md"></div>
                        </div>

                        <input type="hidden" name="latitud" id="latitud">
                        <input type="hidden" name="longitud" id="longitud">

                        <div>
                            <label for="descripcion" class="block font-medium text-sm text-hydro-text-light">Descripción (opcional)</label>
                            <textarea id="descripcion" name="descripcion" rows="4" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">{{ old('descripcion') }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ route('admin.viveros.index') }}" class="text-gray-400 hover:text-white mr-6">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">
                            Crear Vivero
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Coordenadas iniciales (Cochabamba)
            var initialLat = -17.3935;
            var initialLng = -66.1570;
            var initialZoom = 12; // Un zoom más cercano para ver la ciudad

            // Inicializar el mapa
            var map = L.map('map').setView([initialLat, initialLng], initialZoom);

            // Añadir la capa de mapa
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Añadir un marcador arrastrable
            var marker = L.marker([initialLat, initialLng], {
                draggable: true
            }).addTo(map);

            // Campos ocultos
            var latInput = document.querySelector("#latitud");
            var lngInput = document.querySelector("#longitud");

            // Función para actualizar los campos
            function updateInputs(latlng) {
                latInput.value = latlng.lat;
                lngInput.value = latlng.lng;
            }

            // Escuchar el evento de arrastre del marcador
            marker.on('dragend', function() {
                updateInputs(marker.getLatLng());
            });
            
            // --- LÓGICA DE GEOLOCALIZACIÓN (NUEVO) ---
            // 1. Revisa si el navegador soporta la geolocalización
            if ('geolocation' in navigator) {
                // 2. Pide la ubicación actual
                navigator.geolocation.getCurrentPosition(function(position) {
                    // 3. Si el usuario da permiso, actualizamos las coordenadas
                    var userLat = position.coords.latitude;
                    var userLng = position.coords.longitude;
                    var userLatLng = L.latLng(userLat, userLng);

                    // Movemos el mapa y el marcador a la ubicación del usuario
                    map.setView(userLatLng, 13); // Zoom más cercano
                    marker.setLatLng(userLatLng);
                    updateInputs(userLatLng);

                }, function(error) {
                    // Si el usuario niega el permiso o hay un error, no hacemos nada.
                    // El mapa se quedará en la vista por defecto de Bolivia.
                    console.log("Error de geolocalización: ", error.message);
                    updateInputs(marker.getLatLng()); // Actualizamos con la pos. inicial
                });
            } else {
                // Si el navegador no es compatible, inicializamos con los valores por defecto
                console.log("Geolocalización no soportada por este navegador.");
                updateInputs(marker.getLatLng());
            }
            // --- FIN DE LA LÓGICA ---
        });
    </script>
    @endpush
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#select-dueño',{
                placeholder: 'Selecciona un dueño...'
            });
        });
    </script>
    @endpush
</x-app-layout>