<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-text-dark">
            {{ __('Eliminar Cuenta') }}
        </h2>
        <p class="mt-1 text-sm text-text-muted">
            {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán borrados permanentemente. Antes de eliminar tu cuenta, por favor descarga cualquier dato o información que desees conservar.') }}
        </p>
    </header>

    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        {{ __('Eliminar Cuenta') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-white">
            @csrf
            @method('delete')
            <h2 class="text-lg font-medium text-text-dark">
                {{ __('¿Estás seguro de que quieres eliminar tu cuenta?') }}
            </h2>
            <p class="mt-1 text-sm text-text-muted">
                {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán borrados permanentemente. Por favor, ingresa tu contraseña para confirmar que deseas eliminar tu cuenta de forma permanente.') }}
            </p>
            <div class="mt-6">
                <label for="password" class="block font-medium text-sm text-text-dark sr-only">{{ __('Contraseña') }}</label>
                <input id="password" name="password" type="password" class="block mt-1 w-3/4 bg-gray-100 border-gray-300 rounded-md shadow-sm focus:border-strawberry focus:ring-strawberry" placeholder="{{ __('Contraseña') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancelar') }}
                </x-secondary-button>
                <x-danger-button class="ms-3">
                    {{ __('Eliminar Cuenta') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>