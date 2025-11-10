<section>
    <header>
        <h2 class="text-lg font-medium text-text-dark">
            {{ __('Actualizar Contraseña') }}
        </h2>
        <p class="mt-1 text-sm text-text-muted">
            {{ __('Asegúrate de que tu cuenta esté usando una contraseña larga y aleatoria para mantenerla segura.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')
        <div>
            <label for="current_password" class="block font-medium text-sm text-text-dark">{{ __('Contraseña Actual') }}</label>
            <input id="current_password" name="current_password" type="password" class="block mt-1 w-full bg-gray-100 border-gray-300 rounded-md shadow-sm focus:border-strawberry focus:ring-strawberry" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>
        <div>
            <label for="password" class="block font-medium text-sm text-text-dark">{{ __('Nueva Contraseña') }}</label>
            <input id="password" name="password" type="password" class="block mt-1 w-full bg-gray-100 border-gray-300 rounded-md shadow-sm focus:border-strawberry focus:ring-strawberry" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>
        <div>
            <label for="password_confirmation" class="block font-medium text-sm text-text-dark">{{ __('Confirmar Contraseña') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="block mt-1 w-full bg-gray-100 border-gray-300 rounded-md shadow-sm focus:border-strawberry focus:ring-strawberry" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>
        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-sm text-white transition" style="background-color: var(--strawberry);" onmouseover="this.style.backgroundColor='var(--strawberry-dark)'" onmouseout="this.style.backgroundColor='var(--strawberry)'">{{ __('Guardar') }}</button>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>