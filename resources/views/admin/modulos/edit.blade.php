
<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar Módulo en: <span class="text-indigo-500">{{ $vivero->nombre }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('admin.viveros.modulos.update', [$vivero, $modulo]) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="codigo_identificador" value="Código Identificador (Ej: A-01)" />
                            <x-text-input id="codigo_identificador" class="block mt-1 w-full" type="text" name="codigo_identificador" :value="old('codigo_identificador', $modulo->codigo_identificador)" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tipo_sistema" value="Tipo de Sistema (Ej: NFT, Raíz Flotante)" />
                            <x-text-input id="tipo_sistema" class="block mt-1 w-full" type="text" name="tipo_sistema" :value="old('tipo_sistema', $modulo->tipo_sistema)" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="capacidad" value="Capacidad (Nº de plantas)" />
                            <x-text-input id="capacidad" class="block mt-1 w-full" type="number" name="capacidad" :value="old('capacidad', $modulo->capacidad)" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="estado" value="Estado" />
                            <select name="estado" id="estado" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="Disponible" {{ old('estado', $modulo->estado) == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="Ocupado" {{ old('estado', $modulo->estado) == 'Ocupado' ? 'selected' : '' }}>Ocupado</option>
                                <option value="Mantenimiento" {{ old('estado', $modulo->estado) == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="device_id" value="ID del Dispositivo (Hardware)" />
                            <x-text-input id="device_id" class="block mt-1 w-full" type="text" name="device_id" :value="old('device_id', $modulo->hardware_info['device_id'] ?? '')" placeholder="Ej: MOD-A01-HW-12345" />
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Límites para Alertas
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="ph_min" value="pH Mínimo" />
                                    <x-text-input id="ph_min" class="block mt-1 w-full" type="number" step="0.1" name="ph_min" :value="old('ph_min', $modulo->hardware_info['ph_min'] ?? '')" />
                                </div>
                                <div>
                                    <x-input-label for="ph_max" value="pH Máximo" />
                                    <x-text-input id="ph_max" class="block mt-1 w-full" type="number" step="0.1" name="ph_max" :value="old('ph_max', $modulo->hardware_info['ph_max'] ?? '')" />
                                </div>
                                <div>
                                    <x-input-label for="temp_min" value="Temp. Mínima (°C)" />
                                    <x-text-input id="temp_min" class="block mt-1 w-full" type="number" step="0.1" name="temp_min" :value="old('temp_min', $modulo->hardware_info['temp_min'] ?? '')" />
                                </div>
                                <div>
                                    <x-input-label for="temp_max" value="Temp. Máxima (°C)" />
                                    <x-text-input id="temp_max" class="block mt-1 w-full" type="number" step="0.1" name="temp_max" :value="old('temp_max', $modulo->hardware_info['temp_max'] ?? '')" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Actualizar Módulo') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>