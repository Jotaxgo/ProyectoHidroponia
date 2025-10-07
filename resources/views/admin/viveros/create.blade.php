<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hydro-text-light leading-tight">
            Crear Nuevo Vivero
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hydro-card overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold text-white mb-6">Detalles del Vivero</h2>

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

                <form method="POST" action="{{ route('admin.viveros.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="nombre" class="block font-medium text-sm text-hydro-text-light">Nombre del Vivero</label>
                            <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" required autofocus class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        </div>
                        
                        <div>
                            <label for="user_id" class="block font-medium text-sm text-hydro-text-light">Asignar Dueño</label>
                            <select name="user_id" id="user_id" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                                @forelse ($dueños as $dueño)
                                    <option value="{{ $dueño->id }}">{{ $dueño->name }}</option>
                                @empty
                                    <option value="" disabled>No hay "Dueños de Vivero" disponibles para asignar</option>
                                @endforelse
                            </select>
                        </div>

                        <div>
                            <label for="ubicacion" class="block font-medium text-sm text-hydro-text-light">Ubicación</label>
                            <input id="ubicacion" name="ubicacion" type="text" value="{{ old('ubicacion') }}" required class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">
                        </div>

                        <div>
                            <label for="descripcion" class="block font-medium text-sm text-hydro-text-light">Descripción (opcional)</label>
                            <textarea id="descripcion" name="descripcion" rows="4" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white focus:border-hydro-accent-gold focus:ring-hydro-accent-gold rounded-md shadow-sm">{{ old('descripcion') }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ route('admin.viveros.index') }}" class="text-gray-400 hover:text-white mr-6">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">
                            Crear Vivero
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>