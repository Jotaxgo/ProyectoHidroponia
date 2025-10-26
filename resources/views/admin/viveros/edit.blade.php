<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Editar Vivero
            @if (session('info'))
                <div class="bg-blue-500/20 text-blue-300 p-4 rounded mb-6">
                    {{ session('info') }}
                </div>
            @endif
        </h2>
        
        
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold text-white mb-6">Editando Vivero: <span class="text-hydro-accent-gold">{{ $vivero->nombre }}</span></h2>

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

                <form method="POST" action="{{ route('admin.viveros.update', $vivero) }}">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <label for="nombre" class="block font-medium text-sm text-hydro-text-light">Nombre del Vivero</label>
                            <input id="nombre" name="nombre" type="text" value="{{ old('nombre', $vivero->nombre) }}" required autofocus class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        </div>
                        
                        <div>
                            <label for="select-dueño" class="block font-medium text-sm text-hydro-text-light">Asignar Dueño</label>
                            <select name="user_id" id="select-dueño" class="block mt-1 w-full">
                                @foreach ($dueños as $dueño)
                                    <option value="{{ $dueño->id }}" @if($vivero->user_id == $dueño->id) selected @endif>
                                        {{ $dueño->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-hydro-text-light">Ubicación (Arrastra el marcador)</label>
                            <div id="map" style="height: 400px; width: 100%;" class="mt-1 rounded-md z-0"></div>
                        </div>

                        <input type="hidden" name="latitud" id="latitud" value="{{ old('latitud', $vivero->latitud) }}">
                        <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud', $vivero->longitud) }}">

                        <div>
                            <label for="descripcion" class="block font-medium text-sm text-hydro-text-light">Descripción (opcional)</label>
                            <textarea id="descripcion" name="descripcion" rows="4" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">{{ old('descripcion', $vivero->descripcion) }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ route('admin.viveros.index') }}" class="text-gray-400 hover:text-white mr-6">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">
                            Actualizar Vivero
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