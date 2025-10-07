<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Crear Nuevo Usuario
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6" x-data="{ role_id: '{{ old('role_id', $roles->first()->id) }}' }">
                <h2 class="text-2xl font-bold text-white mb-6">Detalles del Usuario</h2>

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

                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block font-medium text-sm text-hydro-text-light">Nombre del Usuario</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        </div>

                        <div>
                            <label for="email" class="block font-medium text-sm text-hydro-text-light">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        </div>
                        
                        <div>
                            <label for="role_id" class="block font-medium text-sm text-hydro-text-light">Rol</label>
                            <select name="role_id" id="role_id" x-model="role_id" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->nombre_rol }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div x-show="role_id == '{{ $roles->where('nombre_rol', 'Dueño de Vivero')->first()->id }}'" x-transition class="pt-6 border-t border-hydro-border space-y-6">
                            <h3 class="text-lg font-medium text-white">Información del Vivero Principal</h3>
                            <div>
                                <label for="vivero_nombre" class="block font-medium text-sm text-hydro-text-light">Nombre del Vivero</label>
                                <input id="vivero_nombre" name="vivero_nombre" type="text" value="{{ old('vivero_nombre') }}" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="vivero_ubicacion" class="block font-medium text-sm text-hydro-text-light">Ubicación</label>
                                <input id="vivero_ubicacion" name="vivero_ubicacion" type="text" value="{{ old('vivero_ubicacion') }}" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="vivero_descripcion" class="block font-medium text-sm text-hydro-text-light">Descripción (opcional)</label>
                                <textarea name="vivero_descripcion" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white rounded-md shadow-sm">{{ old('vivero_descripcion') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-white mr-6">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">
                            Crear y Enviar Invitación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>