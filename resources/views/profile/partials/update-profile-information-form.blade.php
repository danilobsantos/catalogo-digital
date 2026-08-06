<section class="rounded-2xl border border-neutral-200 bg-white p-6 sm:p-8 shadow-xs">
    <header>
        <h2 class="font-display text-lg font-bold tracking-tight text-neutral-900">Informações do Perfil</h2>

        <p class="mt-1 text-sm text-neutral-500">
            Atualize seu nome, e-mail e WhatsApp de contato.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nome" />
            <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" name="email" type="email" class="mt-1.5 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-neutral-600">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm font-semibold text-primary-600 hover:text-primary-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-success-500">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="whatsapp" value="WhatsApp" />
            <x-text-input id="whatsapp" name="whatsapp" type="tel" inputmode="numeric" class="mt-1.5 block w-full"
                          :value="old('whatsapp', $user->whatsapp)" placeholder="5535988119922"
                          autocomplete="tel-national" oninput="this.value = this.value.replace(/\D/g, '')" />
            <x-input-error class="mt-2" :messages="$errors->get('whatsapp')" />
            <p class="mt-1.5 text-xs text-neutral-500">Somente números, com DDI — ex.: 5535988119922</p>
        </div>

        <div class="flex items-center gap-4 pt-1">
            <x-primary-button>Salvar</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-semibold text-success-500"
                >Salvo!</p>
            @endif
        </div>
    </form>
</section>
