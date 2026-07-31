<x-layouts.public
    :title="config('catalog.company.name').' — Catálogo Oficial'"
    description="Botinas e calçados de couro legítimo com curadoria especializada. Catálogo digital completo com orçamento e atendimento via WhatsApp."
    image="{{ url('favicon.png') }}"
>
    {{-- Hero / Banner Destaque --}}
    <section class="py-8 lg:py-12">
        <div class="container-app">
            @if ($banners->isNotEmpty())
                <div class="grid gap-6 lg:grid-cols-2">
                    @foreach ($banners as $i => $banner)
                        @php
                            $isHero = $banner->position === 'hero' || $i === 0;
                            $aspectClass = $isHero ? 'min-h-[70vh] sm:min-h-[60vh] lg:min-h-[70vh] lg:col-span-2' : 'min-h-[300px] sm:min-h-[380px]';
                            $titleClass = $isHero ? 'text-3xl sm:text-4xl lg:text-5xl' : 'text-2xl sm:text-3xl lg:text-4xl';
                            $hasImage = !empty($banner->image_path);
                        @endphp
                        <article class="{{ $aspectClass }} group relative overflow-hidden rounded-3xl bg-[#F4F1EA] border border-[#E6E1D5] shadow-sm flex items-end">
                            @if ($hasImage)
                                <img src="{{ asset('storage/' . $banner->image_path) }}"
                                     alt="{{ $banner->image_alt ?? $banner->title }}"
                                     class="absolute inset-0 size-full object-cover group-hover:scale-105 transition-transform duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#1C1915]/90 via-[#1C1915]/50 to-transparent"></div>
                            @endif

                            <div class="relative z-10 p-6 sm:p-10 lg:p-12 w-full">
                                <div class="max-w-2xl">
                                    <h1 class="{{ $titleClass }} font-display font-bold tracking-tight {{ $hasImage ? 'text-white drop-shadow-sm' : 'text-[#1C1915]' }} leading-tight">
                                        {{ $banner->title }}
                                    </h1>
                                    @if ($banner->subtitle)
                                        <p class="mt-3 text-sm sm:text-base {{ $hasImage ? 'text-white/90' : 'text-[#544D42]' }} max-w-xl leading-relaxed">
                                            {{ $banner->subtitle }}
                                        </p>
                                    @endif
                                    @if ($banner->cta_label)
                                        <a href="{{ $banner->cta_url ?? ($banner->cta_route_name ? route($banner->cta_route_name) : route('public.products.index')) }}"
                                           class="inline-flex mt-6 items-center gap-2 rounded-full {{ $hasImage ? 'bg-[#ff8400] text-white hover:bg-[#A84D29]' : 'bg-[#1C1915] text-[#FAFAF7] hover:bg-[#ff8400]' }} px-6 py-3 text-xs sm:text-sm font-bold uppercase tracking-wider transition shadow-md">
                                            {{ $banner->cta_label }}
                                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                {{-- Fallback Hero Banner --}}
                <div class="rounded-3xl bg-[#F4F1EA] border border-[#E6E1D5] min-h-[114vh] sm:min-h-[133vh] lg:min-h-[152vh] p-8 sm:p-14 lg:p-16 flex items-center">
                    <div class="max-w-2xl space-y-4">
                        <span class="inline-block px-3 py-1 rounded-full bg-[#FAF6F0] text-[#ff8400] text-xs font-bold uppercase tracking-widest">
                            Coleção Oficial {{ date('Y') }}
                        </span>
                        <h1 class="text-3xl sm:text-5xl font-display font-bold tracking-tight text-[#1C1915] leading-tight">
                            Botinas & Calçados de Couro Legítimo
                        </h1>
                        <p class="text-base text-[#544D42] leading-relaxed">
                            Confira nosso catálogo completo de botinas de segurança, linha casual, coturnos e modelos com C.A. Atendimento e pedidos diretos via WhatsApp.
                        </p>
                        <div class="pt-4 flex flex-wrap gap-4">
                            <a href="{{ route('public.products.index') }}"
                               class="inline-flex items-center gap-2 rounded-full bg-[#ff8400] text-white px-6 py-3.5 text-xs font-bold uppercase tracking-wider hover:bg-[#A84D29] transition shadow-md">
                                Ver Catálogo Completo
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('public.categories.index') }}"
                               class="inline-flex items-center gap-2 rounded-full bg-white border border-[#E6E1D5] text-[#28231C] px-6 py-3.5 text-xs font-bold uppercase tracking-wider hover:bg-[#FAFAF7] transition">
                                Explorar por Linha
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Categorias em destaque --}}
    @if ($featuredCategories->isNotEmpty())
        <section class="container-app py-10 lg:py-14">
            <header class="flex items-end justify-between mb-8 pb-4 border-b border-[#E6E1D5]">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">Linhas Principais</p>
                    <h2 class="text-2xl sm:text-3xl font-display font-bold tracking-tight text-[#1C1915] mt-1">Nossas Categorias</h2>
                </div>
                <a href="{{ route('public.categories.index') }}" class="text-xs font-bold text-[#ff8400] hover:underline uppercase tracking-wider">
                    Ver todas →
                </a>
            </header>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredCategories as $category)
                    <a href="{{ route('public.categories.show', $category->slug) }}"
                       class="group relative overflow-hidden rounded-2xl bg-white border border-[#E6E1D5] p-6 transition-all duration-300 hover:border-[#ff8400]/50 hover:shadow-md">
                        <span class="text-[11px] font-bold uppercase tracking-widest text-[#736A5B]">Linha Especial</span>
                        <h3 class="mt-2 text-xl font-display font-bold text-[#1C1915] group-hover:text-[#ff8400] transition">{{ $category->name }}</h3>
                        <p class="mt-2 text-xs text-[#544D42] leading-relaxed line-clamp-2">{{ $category->description }}</p>
                        <div class="mt-4 pt-3 flex items-center justify-between text-xs font-bold text-[#ff8400]">
                            <span>Conhecer coleção</span>
                            <span class="group-hover:translate-x-1 transition">→</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Produtos destaques --}}
    @if ($featuredProducts->isNotEmpty())
        <section class="container-app py-10 lg:py-14">
            <header class="flex items-end justify-between mb-8 pb-4 border-b border-[#E6E1D5]">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">Curadoria</p>
                    <h2 class="text-2xl sm:text-3xl font-display font-bold tracking-tight text-[#1C1915] mt-1">Produtos em Destaque</h2>
                </div>
                <a href="{{ route('public.products.index') }}" class="text-xs font-bold text-[#ff8400] hover:underline uppercase tracking-wider">
                    Ver catálogo →
                </a>
            </header>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- Lançamentos / Novidades --}}
    @if ($newArrivals->isNotEmpty())
        <section class="container-app py-10 lg:py-14">
            <header class="flex items-end justify-between mb-8 pb-4 border-b border-[#E6E1D5]">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#D97706]">Novidades</p>
                    <h2 class="text-2xl sm:text-3xl font-display font-bold tracking-tight text-[#1C1915] mt-1">Lançamentos Recentes</h2>
                </div>
                <a href="{{ route('public.products.index') }}" class="text-xs font-bold text-[#ff8400] hover:underline uppercase tracking-wider">
                    Ver todos →
                </a>
            </header>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($newArrivals as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- Banner CTA WhatsApp --}}
    <section class="container-app py-12 lg:py-16">
        <div class="rounded-3xl bg-[#F4F1EA] border border-[#E6E1D5] p-8 sm:p-12 lg:p-16 text-center shadow-sm">
            <span class="inline-block px-3 py-1 rounded-full bg-[#FAF6F0] text-[#047857] text-xs font-bold uppercase tracking-widest mb-3">
                Atendimento Imediato
            </span>
            <h2 class="text-2xl sm:text-4xl font-display font-bold tracking-tight text-[#1C1915]">
                Dúvidas ou Orçamentos no Atacado e Varejo?
            </h2>
            <p class="mt-3 text-sm sm:text-base text-[#544D42] max-w-xl mx-auto leading-relaxed">
                Fale diretamente com nossa equipe via WhatsApp. Consulte disponibilidade de numeração, fichas técnicas e prazos de envio.
            </p>
            <div class="mt-8">
                <a href="{{ \App\Helpers\WhatsappLink::build(config('catalog.whatsapp.message'), ['produto' => 'Catálogo Geral', 'codigo' => 'geral']) }}"
                   target="_blank" rel="noopener"
                   class="inline-flex items-center gap-3 rounded-full bg-[#047857] px-8 py-4 text-sm font-bold text-white hover:bg-[#065F46] transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                        <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                    </svg>
                    <span>Falar com Consultor</span>
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
