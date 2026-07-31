<div class="container-app py-8 lg:py-14">
    <header class="mb-8 flex flex-col gap-2 border-b border-[#E6E1D5] pb-6">
        <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">Catálogo Oficial</p>
        <h1 class="text-3xl lg:text-4xl font-display font-bold tracking-tight text-[#1C1915]">Buscar Produtos</h1>
        <p class="text-sm text-[#544D42] max-w-2xl leading-relaxed">
            Encontrados <strong class="text-[#1C1915] font-bold">{{ $products->total() }}</strong> produtos. Use a barra de busca e os filtros laterais para refinar sua pesquisa.
        </p>
    </header>

    <div class="grid gap-8 lg:grid-cols-[280px_1fr]">

        {{-- Sidebar / Filtros --}}
        <aside x-data="{ open: false }" class="space-y-6">
            {{-- Mobile Trigger Button --}}
            <button @click="open = true"
                    type="button"
                    class="lg:hidden w-full flex items-center justify-center gap-2 rounded-xl bg-white border border-[#E6E1D5] py-3 text-sm font-bold text-[#1C1915] shadow-sm">
                <svg class="size-4 text-[#ff8400]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0m-9.75 0h9.75"/>
                </svg>
                Filtrar Produtos
            </button>

            {{-- Filter Container (Drawer em Mobile / Card em Desktop) --}}
            <div class="space-y-6 bg-white rounded-2xl border border-[#E6E1D5] p-5 shadow-sm"
                 :class="open ? 'block fixed inset-0 z-50 bg-[#FAFAF7] p-6 overflow-y-auto' : 'hidden lg:block'">

                <div class="flex items-center justify-between pb-3 border-b border-[#F4F1EA]">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-[#1C1915]">Filtros do Catálogo</h3>
                    <div class="flex items-center gap-3">
                        <button type="button" wire:click="clearFilters" class="text-xs font-bold text-[#ff8400] uppercase hover:underline">
                            Limpar
                        </button>
                        <button @click="open = false" type="button" class="lg:hidden text-xs font-bold text-[#544D42] bg-[#E6E1D5] px-2.5 py-1 rounded">
                            Fechar ✕
                        </button>
                    </div>
                </div>

                {{-- Campo de Busca --}}
                <div>
                    <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Pesquisa Rápida</label>
                    <div class="relative mt-1.5">
                        <input type="search" wire:model.live.debounce.300ms="q"
                               placeholder="Nome, código, modelo…"
                               class="w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2.5 text-sm text-[#1C1915] placeholder-[#9E9585] focus:border-[#ff8400] focus:ring-1 focus:ring-[#ff8400]">
                    </div>
                </div>

                {{-- Categorias / Linhas --}}
                @if (count($categories) > 0)
                    <div>
                        <h4 class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Linhas & Categorias</h4>
                        <ul class="mt-2 space-y-1 max-h-48 overflow-y-auto pr-1">
                            @foreach ($categories as $c)
                                <li>
                                    <button type="button" wire:click="$set('category', '{{ $c->slug === $category ? '' : $c->slug }}')"
                                            class="w-full text-left text-xs font-medium px-3 py-2 rounded-lg transition {{ $c->slug === $category ? 'bg-[#ff8400] text-white font-bold' : 'text-[#3D372E] hover:bg-[#F4F1EA]' }}">
                                        {{ $c->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Coleções --}}
                @if (count($collections) > 0)
                    <div>
                        <h4 class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Coleção</h4>
                        <ul class="mt-2 space-y-1">
                            @foreach ($collections as $c)
                                <li>
                                    <button type="button" wire:click="$set('collection', '{{ $c->slug === $collection ? '' : $c->slug }}')"
                                            class="w-full text-left text-xs font-medium px-3 py-2 rounded-lg transition {{ $c->slug === $collection ? 'bg-[#ff8400] text-white font-bold' : 'text-[#3D372E] hover:bg-[#F4F1EA]' }}">
                                        {{ $c->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Tipos de Couro --}}
                @if (count($leathers) > 0)
                    <div>
                        <h4 class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Material / Couro</h4>
                        <ul class="mt-2 space-y-1 max-h-40 overflow-y-auto pr-1">
                            @foreach ($leathers as $l)
                                <li>
                                    <button type="button" wire:click="$set('leather', '{{ $l->leather === $leather ? '' : $l->leather }}')"
                                            class="w-full text-left text-xs font-medium px-3 py-1.5 rounded-lg flex items-center justify-between transition {{ $l->leather === $leather ? 'bg-[#ff8400] text-white font-bold' : 'text-[#3D372E] hover:bg-[#F4F1EA]' }}">
                                        <span>{{ $l->leather }}</span>
                                        <span class="text-[10px] opacity-75 font-mono">({{ $l->c }})</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Solados --}}
                @if (count($soles) > 0)
                    <div>
                        <h4 class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Tipo de Solado</h4>
                        <ul class="mt-2 space-y-1 max-h-40 overflow-y-auto pr-1">
                            @foreach ($soles as $s)
                                <li>
                                    <button type="button" wire:click="$set('sole', '{{ $s->sole === $sole ? '' : $s->sole }}')"
                                            class="w-full text-left text-xs font-medium px-3 py-1.5 rounded-lg flex items-center justify-between transition {{ $s->sole === $sole ? 'bg-[#ff8400] text-white font-bold' : 'text-[#3D372E] hover:bg-[#F4F1EA]' }}">
                                        <span>{{ $s->sole }}</span>
                                        <span class="text-[10px] opacity-75 font-mono">({{ $s->c }})</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Filtros Booleanos --}}
                <div class="space-y-2.5 pt-3 border-t border-[#F4F1EA]">
                    <label class="flex items-center gap-2.5 text-xs font-medium text-[#28231C] cursor-pointer">
                        <input type="checkbox" wire:model.live="hasCa" class="rounded border-[#E6E1D5] text-[#ff8400] focus:ring-[#ff8400]">
                        Somente com C.A. (Segurança)
                    </label>
                    <label class="flex items-center gap-2.5 text-xs font-medium text-[#28231C] cursor-pointer">
                        <input type="checkbox" wire:model.live="onlyNew" class="rounded border-[#E6E1D5] text-[#ff8400] focus:ring-[#ff8400]">
                        Lançamentos & Novidades
                    </label>
                    <label class="flex items-center gap-2.5 text-xs font-medium text-[#28231C] cursor-pointer">
                        <input type="checkbox" wire:model.live="onlyFeatured" class="rounded border-[#E6E1D5] text-[#ff8400] focus:ring-[#ff8400]">
                        Modelos em Destaque
                    </label>
                </div>
            </div>
        </aside>

        {{-- Grade de Resultados --}}
        <div>
            {{-- Toolbar: Ordenação e Contador --}}
            <div class="flex flex-wrap items-center justify-between gap-4 bg-white rounded-xl border border-[#E6E1D5] p-4 mb-6 shadow-sm">
                <p class="text-xs font-semibold text-[#544D42]">
                    Mostrando <strong class="text-[#1C1915] font-bold">{{ $products->count() }}</strong> de {{ $products->total() }} modelo(s)
                </p>
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold uppercase tracking-wider text-[#736A5B]">Ordenar por:</label>
                    <select wire:model.live="sort" class="rounded-lg border border-[#E6E1D5] bg-[#FAFAF7] px-3 py-1.5 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                        <option value="relevance">Relevância</option>
                        <option value="newest">Mais recentes</option>
                        <option value="oldest">Mais antigos</option>
                        <option value="views">Mais acessados</option>
                    </select>
                </div>
            </div>

            @if ($products->isEmpty())
                <div class="rounded-2xl bg-white border border-dashed border-[#E6E1D5] p-12 text-center">
                    <div class="size-12 rounded-full bg-[#FAF6F0] text-[#ff8400] flex items-center justify-center mx-auto mb-3">
                        <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    </div>
                    <h3 class="font-display font-bold text-base text-[#1C1915]">Nenhum produto encontrado</h3>
                    <p class="mt-1 text-xs text-[#544D42]">Tente ajustar os termos da busca ou limpar os filtros aplicados.</p>
                    <button type="button" wire:click="clearFilters" class="mt-4 inline-flex items-center gap-2 rounded-full bg-[#ff8400] px-5 py-2 text-xs font-bold text-white uppercase tracking-wider hover:bg-[#A84D29]">
                        Limpar Filtros
                    </button>
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

