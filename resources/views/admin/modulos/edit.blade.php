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