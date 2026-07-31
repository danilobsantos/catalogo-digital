<x-layouts.public
    :title="$page->meta_title ?? $page->title.' — '.config('catalog.company.name')"
    :description="$page->meta_description ?? $page->subtitle ?? $page->title"
>
    <article class="container-app py-12 lg:py-20 max-w-3xl prose-base">
        <header class="mb-10">
            <nav class="text-xs text-neutral-500 mb-6">
                <a href="{{ route('home') }}" class="hover:underline">Início</a>
                <span class="mx-2">›</span>
                <span>{{ $page->title }}</span>
            </nav>
            <p class="text-xs uppercase tracking-[0.3em] text-neutral-500">{{ ucfirst($page->slug) }}</p>
            <h1 class="mt-2 text-4xl lg:text-5xl font-display font-semibold tracking-tight">{{ $page->title }}</h1>
            @if ($page->subtitle)
                <p class="mt-3 text-lg text-neutral-600 dark:text-neutral-300">{{ $page->subtitle }}</p>
            @endif
        </header>

        <section class="prose prose-neutral dark:prose-invert max-w-none">
            {!! $content !!}
        </section>

        <footer class="mt-12 pt-8 border-t border-neutral-100 dark:border-neutral-900 flex justify-between text-sm">
            <a href="{{ route('home') }}" class="text-neutral-500 hover:underline">← Voltar</a>
            <a href="{{ route('public.contact') }}" class="text-emerald-600 hover:underline">Falar com a equipe →</a>
        </footer>
    </article>
</x-layouts.public>
