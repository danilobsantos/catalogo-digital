<x-layouts.admin title="Meu Perfil · CJ Calçados">
    <header class="border-b border-neutral-200 pb-5">
        <p class="text-xs font-bold uppercase tracking-[0.25em] text-primary-500">Conta</p>
        <h1 class="mt-1 text-2xl lg:text-3xl font-display font-bold tracking-tight text-neutral-900">Meu Perfil</h1>
    </header>

    <div class="mt-8 max-w-3xl space-y-6">
        @include('profile.partials.update-profile-information-form')

        @include('profile.partials.update-password-form')

        @include('profile.partials.delete-user-form')
    </div>
</x-layouts.admin>
