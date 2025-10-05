<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Nuevo Vivero') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="bg-red-500 text-white p-4 rounded mb-4">
                            <strong>¡Ups!</strong> Hubo algunos problemas con tu entrada.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.viveros.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="nombre" :value="__('Nombre')" />
                            <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="user_id" :value="__('Asignar Dueño')" />
                            <select name="user_id" id="user_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                @forelse ($dueños as $dueño)
                                    <option value="{{ $dueño->id }}">{{ $dueño->name }}</option>
                                @empty
                                    <option value="" disabled>No hay "Dueños de Vivero" disponibles</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="ubicacion" :value="__('Ubicación')" />
                            <x-text-input id="ubicacion" class="block mt-1 w-full" type="text" name="ubicacion" :value="old('ubicacion')" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="descripcion" :value="__('Descripción (opcional)')" />
                            <textarea id="descripcion" name="descripcion" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Crear Vivero') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>