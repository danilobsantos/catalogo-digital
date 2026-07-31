<x-layouts.public title="Erro 500 — {{ config('catalog.company.name') }}">
    <section class="container-app min-h-[70vh] flex items-center justify-center py-20 lg:py-28">
        <div class="text-center max-w-2xl">
            <p class="text-xs uppercase tracking-[0.3em] text-neutral-500">Erro interno</p>
            <p class="mt-4 font-display text-[120px] lg:text-[180px] leading-none font-semibold tracking-tighter">500</p>
            <h1 class="mt-4 text-3xl lg:text-4xl font-display font-semibold tracking-tight">Algo deu errado</h1>
            <p class="mt-4 text-neutral-600 dark:text-neutral-300">
                Nossa equipe foi notificada. Você pode tentar novamente — ou entrar em contato imediato pelo WhatsApp.
            </p>
            @if (config('app.debug'))
                <p class="mt-4 text-xs font-mono text-neutral-500 bg-neutral-100 dark:bg-neutral-900 px-3 py-1 inline-block rounded">
                    {{ $exception?->getMessage() ?? 'Erro inesperado' }}
                </p>
            @endif
            <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('home') }}"
                   class="rounded-full bg-neutral-900 dark:bg-neutral-100 text-neutral-0 dark:text-neutral-900 px-5 py-2.5 text-sm font-medium hover:opacity-90">
                    Recarregar página
                </a>
                <a href="{{ config('catalog.whatsapp.url') ?? 'https://wa.me/'.config('catalog.whatsapp.number') }}" target="_blank" rel="noopener"
                   class="rounded-full bg-emerald-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-emerald-600">
                    Avisar pelo WhatsApp
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
