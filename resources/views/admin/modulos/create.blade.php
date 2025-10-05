<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Añadir Módulo a: <span class="text-indigo-500">{{ $vivero->nombre }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Error de Validación!</strong>
                            <span class="block sm:inline">Por favor, corrige los siguientes errores:</span>
                            <ul class="mt-3 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    

                    <form method="POST" action="{{ route('admin.viveros.modulos.store', $vivero) }}">
                        @csrf

                        <div>
                            <x-input-label for="codigo_identificador" value="Código Identificador (Ej: A-01)" />
                            <x-text-input id="codigo_identificador" class="block mt-1 w-full" type="text" name="codigo_identificador" :value="old('codigo_identificador')" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="capacidad" value="Capacidad (Nº de plantas)" />
                            <x-text-input id="capacidad" class="block mt-1 w-full" type="number" name="capacidad" :value="old('capacidad')" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="device_id" value="ID del Dispositivo (Hardware)" />
                            <x-text-input id="device_id" class="block mt-1 w-full" type="text" name="device_id" :value="old('device_id')" placeholder="Ej: MOD-A01-HW-12345" />
                        </div>

        

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Límites para Alertas (Opcional)
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="ph_min" value="pH Mínimo" />
                                    <x-text-input id="ph_min" class="block mt-1 w-full" type="number" step="0.1" name="ph_min" :value="old('ph_min')" />
                                </div>
                                <div>
                                    <x-input-label for="ph_max" value="pH Máximo" />
                                    <x-text-input id="ph_max" class="block mt-1 w-full" type="number" step="0.1" name="ph_max" :value="old('ph_max')" />
                                </div>
                                <div>
                                    <x-input-label for="temp_min" value="Temp. Mínima (°C)" />
                                    <x-text-input id="temp_min" class="block mt-1 w-full" type="number" step="0.1" name="temp_min" :value="old('temp_min')" />
                                </div>
                                <div>
                                    <x-input-label for="temp_max" value="Temp. Máxima (°C)" />
                                    <x-text-input id="temp_max" class="block mt-1 w-full" type="number" step="0.1" name="temp_max" :value="old('temp_max')" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Añadir Módulo') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>