<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ajustes de Sensores para el Vivero: <span class="text-hydro-accent-gold">{{ $vivero->nombre }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('admin.viveros.settings.update', $vivero) }}" method="POST">
                        @csrf
                        <div class="space-y-8">

                            @php
                                $sensor_definitions = [
                                    'ph' => ['label' => 'PH', 'step' => '0.1'],
                                    'ec' => ['label' => 'EC (µS/cm)', 'step' => '0.01'],
                                    'temperatura' => ['label' => 'Temperatura (°C)', 'step' => '0.1'],
                                    'humedad' => ['label' => 'Humedad (%)', 'step' => '1'],
                                    'luz' => ['label' => 'Luz (lux)', 'step' => '100'],
                                ];
                            @endphp

                            @foreach ($sensor_definitions as $sensor_key => $sensor_data)
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ $sensor_data['label'] }}</h3>
                                    <p class="mt-1 text-sm text-gray-600">
                                        Define el rango ideal para el sensor de {{ strtolower($sensor_data['label']) }}. Las alertas se activarán si los valores salen de este rango.
                                    </p>
                                    <div class="mt-4 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                        <div>
                                            <label for="{{ $sensor_key }}_min" class="block text-sm font-medium text-gray-700">Valor Mínimo</label>
                                            <div class="mt-1">
                                                <input type="number" step="{{ $sensor_data['step'] }}" name="{{ $sensor_key }}_min" id="{{ $sensor_key }}_min"
                                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                       value="{{ old($sensor_key.'_min', $settings[$sensor_key]['min'] ?? '') }}">
                                            </div>
                                            @error("{$sensor_key}_min")
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="{{ $sensor_key }}_max" class="block text-sm font-medium text-gray-700">Valor Máximo</label>
                                            <div class="mt-1">
                                                <input type="number" step="{{ $sensor_data['step'] }}" name="{{ $sensor_key }}_max" id="{{ $sensor_key }}_max"
                                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                       value="{{ old($sensor_key.'_max', $settings[$sensor_key]['max'] ?? '') }}">
                                            </div>
                                            @error("{$sensor_key}_max")
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-6">
                            @endforeach

                        </div>

                        <div class="pt-5">
                            <div class="flex justify-end">
                                <a href="{{ route('admin.viveros.modulos.index', $vivero) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancelar
                                </a>
                                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gradient-to-r from-[#9c0000] to-[#ff4b65] hover:shadow-lg transition">
                                    Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
