<x-layouts.public
    title="Catálogo de Produtos — {{ config('catalog.company.name') }}"
    description="Veja todo o catálogo de botinas, coturnos e calçados infantis."
>
    <section class="container-app py-12 lg:py-16">
        <header class="mb-10 flex flex-col gap-3">
            <p class="text-xs uppercase tracking-[0.3em] text-neutral-500">Catálogo</p>
            <h1 class="text-4xl lg:text-5xl font-display font-semibold tracking-tight">Todos os produtos</h1>
            <p class="text-neutral-600 dark:text-neutral-300 max-w-2xl">
                {{ $products->total() }} produtos disponíveis. Use o WhatsApp para enviar fotos, medidas ou ficha técnica diretamente.
            </p>
        </header>

        @if ($products->isEmpty())
            <div class="rounded-2xl border border-dashed border-neutral-300 dark:border-neutral-700 p-12 text-center">
                <p class="text-neutral-500">Nenhum produto disponível no momento.</p>
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <div class="mt-12">
                {{ $products->links('public.catalog.pagination') }}
            </div>
        @endif
    </section>
</x-layouts.public>
