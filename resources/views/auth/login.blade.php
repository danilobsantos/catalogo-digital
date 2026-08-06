<x-guest-layout>
    <div class="px-6 sm:px-8 py-8">
        <div class="mb-6">
            <h1 class="font-display text-xl font-extrabold tracking-tight text-neutral-900">Acessar Painel CJ</h1>
            <p class="mt-1 text-sm text-neutral-500">Entre para gerenciar o catálogo digital.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('E-mail')" />
                <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between">
                    <x-input-label for="password" :value="__('Senha')" />
                    @if (Route::has('password.request'))
                        <a class="text-xs font-semibold text-primary-600 hover:text-primary-700" href="{{ route('password.request') }}">
                            {{ __('Esqueceu a senha?') }}
                        </a>
                    @endif
                </div>

                <x-text-input id="password" class="block mt-1.5 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-neutral-300 text-primary-500 shadow-sm focus:ring-primary-500 focus:ring-offset-2" name="remember">
                    <span class="ms-2 text-sm text-neutral-600">{{ __('Lembrar de mim') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end pt-1">
                <x-primary-button>
                    {{ __('Entrar') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
