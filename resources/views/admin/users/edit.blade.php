<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Editar Usuario
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold text-white mb-6">Editando a: <span class="text-hydro-accent-gold">{{ $user->name }}</span></h2>

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

                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block font-medium text-sm text-hydro-text-light">Nombre del Usuario</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        </div>

                        <div>
                            <label for="email" class="block font-medium text-sm text-hydro-text-light">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        </div>
                        
                        <div>
                            <label for="role_id" class="block font-medium text-sm text-hydro-text-light">Rol</label>
                            <select name="role_id" id="role_id" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @if($user->role_id == $role->id) selected @endif>{{ $role->nombre_rol }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-white mr-6">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">
                            Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>