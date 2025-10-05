<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Vivero') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('admin.viveros.update', $vivero) }}">
                        @csrf
                        @method('PUT') 
                        <div>
                            <x-input-label for="nombre" :value="__('Nombre')" />
                            <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre', $vivero->nombre)" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="user_id" :value="__('Dueño del Vivero')" />
                            <select name="user_id" id="user_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                @foreach ($dueños as $dueño)
                                    <option value="{{ $dueño->id }}" @if($vivero->user_id == $dueño->id) selected @endif>
                                        {{ $dueño->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="ubicacion" :value="__('Ubicación')" />
                            <x-text-input id="ubicacion" class="block mt-1 w-full" type="text" name="ubicacion" :value="old('ubicacion', $vivero->ubicacion)" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="descripcion" :value="__('Descripción (opcional)')" />
                            <textarea id="descripcion" name="descripcion" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('descripcion', $vivero->descripcion) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Actualizar Vivero') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>