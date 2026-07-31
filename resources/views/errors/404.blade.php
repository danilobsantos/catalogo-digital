<x-layouts.public title="Página não encontrada — 404 · CJ Calçados" :header="'Página não encontrada'">
    <section class="container-app min-h-[70vh] flex items-center justify-center py-20 lg:py-28">
        <div class="text-center max-w-2xl">
            <p class="text-xs uppercase tracking-[0.3em] text-neutral-500">Calçados & Tradições</p>
            <p class="mt-4 font-display text-[120px] lg:text-[180px] leading-none font-semibold tracking-tighter">404</p>
            <h1 class="mt-4 text-3xl lg:text-4xl font-display font-semibold tracking-tight">Página não encontrada</h1>
            <p class="mt-4 text-neutral-600 dark:text-neutral-300">
                O produto ou página que você procura não está mais aqui. Explore o catálogo completo ou
                fale com nosso time pelo WhatsApp.
            </p>
            <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('home') }}"
                   class="rounded-full bg-neutral-900 dark:bg-neutral-100 text-neutral-0 dark:text-neutral-900 px-5 py-2.5 text-sm font-medium hover:opacity-90">
                    Voltar ao catálogo
                </a>
                <a href="{{ route('public.products.index') }}"
                   class="rounded-full border border-neutral-300 dark:border-neutral-700 px-5 py-2.5 text-sm font-medium hover:border-neutral-400">
                    Ver produtos
                </a>
                <a href="{{ config('catalog.whatsapp.url') ?? 'https://wa.me/'.config('catalog.whatsapp.number') }}" target="_blank" rel="noopener"
                   class="rounded-full bg-emerald-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-emerald-600">
                    Falar no WhatsApp
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
