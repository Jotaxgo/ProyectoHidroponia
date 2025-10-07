<section>
    <header>
        <h2 class="text-lg font-medium text-hydro-text-light">
            {{ __('Información del Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __("Actualiza la información de tu perfil y tu dirección de correo electrónico.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block font-medium text-sm text-hydro-text-light">{{ __('Nombre') }}</label>
            <input id="name" name="name" type="text" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white rounded-md shadow-sm" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block font-medium text-sm text-hydro-text-light">{{ __('Correo Electrónico') }}</label>
            <input id="email" name="email" type="email" class="block mt-1 w-full bg-hydro-dark border-hydro-border text-white rounded-md shadow-sm" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-200">
                        {{ __('Tu dirección de correo electrónico no está verificada.') }}
                        <button form="send-verification" class="underline text-sm text-gray-400 hover:text-gray-100 rounded-md">
                            {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-400">
                            {{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de correo.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-hydro-accent-gold border border-transparent rounded-md font-semibold text-xs text-hydro-dark uppercase tracking-widest hover:opacity-90 transition">{{ __('Guardar') }}</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-400">{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>