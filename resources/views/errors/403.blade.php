<x-layouts.public title="Acesso negado — 403 · CJ Calçados">
    <section class="container-app min-h-[70vh] flex items-center justify-center py-20 lg:py-28">
        <div class="text-center max-w-2xl">
            <p class="text-xs uppercase tracking-[0.3em] text-neutral-500">Permissão</p>
            <p class="mt-4 font-display text-[120px] lg:text-[180px] leading-none font-semibold tracking-tighter">403</p>
            <h1 class="mt-4 text-3xl lg:text-4xl font-display font-semibold tracking-tight">Acesso negado</h1>
            <p class="mt-4 text-neutral-600 dark:text-neutral-300">
                Você não tem permissão para visualizar esta página. Caso seu papel tenha mudado,
                faça logout e entre novamente.
            </p>
            <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('home') }}" class="rounded-full bg-neutral-900 dark:bg-neutral-100 text-neutral-0 dark:text-neutral-900 px-5 py-2.5 text-sm font-medium hover:opacity-90">
                    Voltar ao site
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="rounded-full border border-neutral-300 dark:border-neutral-700 px-5 py-2.5 text-sm font-medium hover:border-neutral-400">
                        Sair & reentrar
                    </button>
                </form>
            </div>
        </div>
    </section>
</x-layouts.public>
