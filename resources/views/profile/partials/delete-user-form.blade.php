<section class="rounded-2xl border border-rose-200 bg-rose-50/50 p-6 sm:p-8 shadow-xs">
    <header>
        <h2 class="font-display text-lg font-bold tracking-tight text-neutral-900">Excluir Conta</h2>

        <p class="mt-1 text-sm text-neutral-500">
            Após a exclusão, todos os seus dados serão removidos permanentemente.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="mt-6"
    >Excluir Conta</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="font-display text-lg font-bold text-neutral-900">Tem certeza que deseja excluir sua conta?</h2>

            <p class="mt-1 text-sm text-neutral-500">
                Após a exclusão, todos os seus recursos e dados serão excluídos permanentemente.
                Digite sua senha para confirmar.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Senha" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Senha"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    Excluir Conta
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
