<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            ✏️ <span class="bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent">Editar Usuario</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg overflow-hidden rounded-2xl p-8" style="box-shadow: 0 8px 32px rgba(156, 0, 0, 0.08);">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-[#9c0000] to-[#ff4b65] bg-clip-text text-transparent mb-8">Editando: 👤 {{ $user->name }}</h2>

                @if ($errors->any())
                    <div class="bg-red-500/20 border border-red-500/40 text-red-700 p-4 rounded-lg mb-6">
                        <strong class="font-bold">⚠️ Error de Validación!</strong>
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
                            <label for="nombres" class="block font-semibold text-sm text-[#1a1a1a] mb-2">Nombres</label>
                            <input id="nombres" name="nombres" type="text" value="{{ old('nombres', $user->nombres) }}" required autofocus class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                        </div>

                        <div>
                            <label for="primer_apellido" class="block font-semibold text-sm text-[#1a1a1a] mb-2">Primer Apellido</label>
                            <input id="primer_apellido" name="primer_apellido" type="text" value="{{ old('primer_apellido', $user->primer_apellido) }}" required class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                        </div>

                        <div>
                            <label for="segundo_apellido" class="block font-semibold text-sm text-[#1a1a1a] mb-2">Segundo Apellido (Opcional)</label>
                            <input id="segundo_apellido" name="segundo_apellido" type="text" value="{{ old('segundo_apellido', $user->segundo_apellido) }}" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                        </div>

                        <div>
                            <label for="email" class="block font-semibold text-sm text-[#1a1a1a] mb-2">📧 Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                        </div>
                        
                        <div>
                            <label for="role_id" class="block font-semibold text-sm text-[#1a1a1a] mb-2">🎯 Rol</label>
                            <select name="role_id" id="role_id" class="block w-full px-4 py-2 bg-white border-[#e0e0e0] text-[#1a1a1a] focus:border-[#ff4b65] focus:ring-2 focus:ring-[#ffdef0] rounded-lg shadow-sm transition">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @if($user->role_id == $role->id) selected @endif>{{ $role->nombre_rol }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-[#e0e0e0]">
                        <a href="{{ route('admin.users.index') }}" class="text-[#555555] hover:text-[#9c0000] transition font-medium text-sm">← Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#9c0000] to-[#ff4b65] text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                            💾 Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>