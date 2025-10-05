<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Nuevo Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" x-data="{ role_id: '{{ old('role_id', $roles->first()->id) }}' }">

                    @if ($errors->any())
                        @endif

                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Nombre')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="role_id" :value="__('Rol')" />
                            <select name="role_id" id="role_id" x-model="role_id" class="block mt-1 w-full border-gray-300 ...">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->nombre_rol }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div x-show="role_id == '{{ $roles->where('nombre_rol', 'Dueño de Vivero')->first()->id }}'" x-transition class="mt-6 pt-6 border-t border-gray-300">
                            <h3 class="text-lg font-medium">Información del Vivero Principal</h3>
                            <div class="mt-4">
                                <x-input-label for="vivero_nombre" value="Nombre del Vivero" />
                                <x-text-input id="vivero_nombre" class="block mt-1 w-full" type="text" name="vivero_nombre" :value="old('vivero_nombre')" />
                            </div>
                            <div class="mt-4">
                                <x-input-label for="vivero_ubicacion" value="Ubicación" />
                                <x-text-input id="vivero_ubicacion" class="block mt-1 w-full" type="text" name="vivero_ubicacion" :value="old('vivero_ubicacion')" />
                            </div>
                            <div class="mt-4">
                                <x-input-label for="vivero_descripcion" value="Descripción (opcional)" />
                                <textarea name="vivero_descripcion" class="block mt-1 w-full border-gray-300 ...">{{ old('vivero_descripcion') }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Crear Usuario y Enviar Invitación') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>