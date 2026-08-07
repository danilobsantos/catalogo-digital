<x-layouts.public
    :title="$product->name.' — '.config('catalog.company.name')"
    :description="$product->short_description ?? $product->name"
    :image="$product->images->firstWhere('is_cover', true) ? asset('storage/'.$product->images->firstWhere('is_cover', true)->path) : null"
    :type="'product'"
>
    {{-- JSON-LD Product schema --}}
    @php
        $company = auth()->user()?->activeCompany ?? \App\Domains\Company\Models\Company::where('slug', 'cj-calcados')->first();
        $cover = $product->images->firstWhere('is_cover', true) ?? $product->images->first();
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => $cover ? [asset('storage/'.$cover->path)] : null,
            'description' => $product->short_description ?? trim(Str::limit(strip_tags($product->description ?? ''), 200)),
            'sku' => $product->code.($product->variant_code ? '-'.$product->variant_code : ''),
            'mpn' => (string) $product->id,
            'gtin13' => $product->has_ca ? $product->ca_number : null,
            'brand' => [
                '@type' => 'Brand',
                'name' => $product->brand?->name ?? config('catalog.company.name'),
            ],
            'category' => $product->category?->name,
            'offers' => [
                '@type' => 'Offer',
                'url' => URL::current(),
                'priceCurrency' => 'BRL',
                'price' => $product->has_ca ? '0.00' : null,
                'priceValidUntil' => now()->addYear()->toDateString(),
                'availability' => 'https://schema.org/InStock',
                'itemCondition' => 'https://schema.org/NewCondition',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => config('catalog.company.name'),
                ],
            ],
        ];
        // remover ítens null / vazios
        $schema = array_filter($schema, fn ($v) => $v !== null && $v !== '');

        $breadcrumbs = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Início', 'item' => route('home')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Catálogo', 'item' => route('public.products.index')],
            ],
        ];
        if ($product->category) {
            $breadcrumbs['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $product->category->name,
                'item' => route('public.categories.show', $product->category->slug),
            ];
        }
        $breadcrumbs['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => count($breadcrumbs['itemListElement']) + 1,
            'name' => $product->name,
            'item' => URL::current(),
        ];
    @endphp
    <x-slot:head>
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
        <script type="application/ld+json">{!! json_encode($breadcrumbs, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
    </x-slot:head>
    <article class="container-app py-8 lg:py-14">
        {{-- Breadcrumb --}}
        <nav class="text-xs font-semibold text-[#736A5B] mb-6 flex flex-wrap items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-[#ff8400] transition">Início</a>
            <span class="text-[#9E9585]">/</span>
            <a href="{{ route('public.products.index') }}" class="hover:text-[#ff8400] transition">Catálogo</a>
            @if ($product->category)
                <span class="text-[#9E9585]">/</span>
                <a href="{{ route('public.categories.show', $product->category->slug) }}" class="hover:text-[#ff8400] transition">{{ $product->category->name }}</a>
            @endif
            <span class="text-[#9E9585]">/</span>
            <span class="text-[#1C1915] font-bold">{{ $product->code }}</span>
        </nav>

        <div class="grid gap-8 lg:grid-cols-2 lg:gap-12 items-start">
            {{-- Galeria de Imagens --}}
            <div class="space-y-4" x-data="{ activeImage: '{{ asset('storage/'.(($product->images->firstWhere('is_cover', true) ?? $product->images->first())?->cover_path ?? (($product->images->firstWhere('is_cover', true) ?? $product->images->first())?->path ?? ''))) }}' }">
                @php $cover = $product->images->firstWhere('is_cover', true) ?? $product->images->first(); @endphp
                <div class="aspect-[4/5] overflow-hidden rounded-3xl bg-white border border-[#E6E1D5] shadow-sm relative">
                    @if ($cover)
                        <img :src="activeImage" alt="{{ $product->name }}"
                             loading="eager" fetchpriority="high" decoding="async"
                             width="830" height="1024"
                             class="w-full h-full object-cover transition-all duration-300">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-[#9E9585] p-6 text-center">
                            <svg class="size-16 mb-2 opacity-50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M2.25 21l8.012-9.738m0 0l3.738-4.333M10.262 11.262L21 2.25M10.262 11.262a3 3 0 1 1-4.244-4.243 3 3 0 0 1 4.244 4.243Z"/></svg>
                            <span class="text-sm font-medium">Imagem em atualização</span>
                        </div>
                    @endif
                </div>

                @if ($product->images->count() > 1)
                    <div class="grid grid-cols-4 gap-3">
                        @foreach ($product->images as $image)
                            @php
                                $thumb_843 = asset('storage/'.($image->thumb_path ?? $image->path));
                                $path = asset('storage/'.$image->path);
                            @endphp
                            <button type="button" @click="activeImage = '{{ $thumb_843 }}'"
                                    class="aspect-square overflow-hidden rounded-xl bg-white border border-[#E6E1D5] hover:border-[#ff8400] transition p-0.5 focus:outline-none"
                                    :class="activeImage === '{{ $thumb_843 }}' ? 'ring-2 ring-[#ff8400] border-transparent' : ''">
                                <picture class="block w-full h-full">
                                    @if ($image->thumb_path)
                                        <source srcset="{{ asset('storage/'.$image->thumb_path) }}" type="image/webp">
                                    @endif
                                    <img src="{{ $path }}" alt="{{ $image->alt_text ?? $product->name }}"
                                         loading="lazy" decoding="async"
                                         class="w-full h-full object-cover rounded-lg">
                                </picture>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Informações do Produto --}}
            <div class="bg-white rounded-3xl border border-[#E6E1D5] p-6 sm:p-8 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">
                    Código: {{ $product->code }}{{ $product->variant_code ? '-'.$product->variant_code : '' }}
                </p>
                <h1 class="mt-2 text-2xl sm:text-4xl font-display font-bold tracking-tight text-[#1C1915] leading-tight">{{ $product->name }}</h1>

                @if ($product->subtitle)
                    <p class="mt-2 text-base text-[#544D42] leading-relaxed">{{ $product->subtitle }}</p>
                @endif

                {{-- Badges --}}
                <div class="mt-5 flex flex-wrap gap-2">
                    @if ($product->has_ca)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[#FAF6F0] border border-[#D97706]/30 text-[#D97706] px-3.5 py-1 text-xs font-bold">
                            <svg class="size-3.5 fill-current" viewBox="0 0 20 20"><path d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm-1-11a1 1 0 1 1 2 0v3a1 1 0 1 1-2 0V7Zm1 8a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/></svg>
                            C.A. nº {{ $product->approvals }}
                        </span>
                    @endif
                    @if ($product->is_new)
                        <span class="rounded-full bg-[#c25e38] text-white px-3.5 py-1 text-xs font-bold uppercase tracking-wider">Lançamento</span>
                    @endif
                    @if ($product->collection)
                        <span class="rounded-full bg-[#F4F1EA] text-[#3D372E] border border-[#E6E1D5] px-3.5 py-1 text-xs font-semibold">{{ $product->collection->name }}</span>
                    @endif
                </div>

                {{-- Ficha Técnica Grid --}}
                <div class="mt-6 pt-6 border-t border-[#F4F1EA]">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#736A5B] mb-4">Ficha Técnica Principal</h3>
                    <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-xs sm:text-sm">
                        @foreach (array_filter([
                            'Couro / Cabedal' => $product->leather,
                            'Tipo de Solado' => $product->sole,
                            'Fechamento' => $product->closure,
                            'Biqueira / Bico' => $product->toe_cap,
                            'Peso Aprox.' => $product->weight_grams ? $product->weight_grams.'g' : null,
                        ]) as $label => $value)
                            <div class="bg-[#FAFAF7] rounded-xl border border-[#E6E1D5] p-3">
                                <dt class="text-[10px] font-bold uppercase tracking-wider text-[#736A5B]">{{ $label }}</dt>
                                <dd class="mt-0.5 font-bold text-[#1C1915]">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                {{-- CTA Orçamento WhatsApp --}}
                <div class="mt-8">
                    <a href="{{ \App\Helpers\WhatsappLink::build(config('catalog.whatsapp.message'), ['produto' => $product->name, 'codigo' => $product->code]) }}"
                       target="_blank" rel="noopener"
                       class="flex w-full items-center justify-center gap-3 rounded-2xl bg-[#047857] px-6 py-4 text-sm sm:text-base font-bold text-white shadow-md transition hover:bg-[#065F46]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
  <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
</svg>
                        <span>Solicitar Orçamento</span>
                    </a>
                </div>

            </div>
        </div>

        {{-- Detalhes Adicionais (Descrição, Medidas, Materiais e Cuidados) --}}
        @if ($product->description || ($product->size_chart && count($product->size_chart) > 0) || ($product->materials && count($product->materials) > 0) || ($product->care_instructions && count($product->care_instructions) > 0))
            <div class="mt-8 lg:mt-12 bg-white rounded-3xl border border-[#E6E1D5] p-6 sm:p-10 shadow-sm space-y-10">
                {{-- Descrição detalhada --}}
                @if ($product->description)
                    <section>
                        <h2 class="text-sm sm:text-base font-bold uppercase tracking-wider text-[#1C1915] mb-3">Descrição do Modelo</h2>
                        <p class="text-xs sm:text-sm text-[#544D42] leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
                    </section>
                @endif

                {{-- Tabela de medidas --}}
                @if ($product->size_chart && count($product->size_chart) > 0)
                    <section class="pt-8 border-t border-[#F4F1EA]">
                        <h2 class="text-sm sm:text-base font-bold uppercase tracking-wider text-[#1C1915] mb-4">Tabela de Numeração & Medidas (cm)</h2>
                        <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-8 lg:grid-cols-10 gap-2.5 text-center">
                            @foreach ($product->size_chart as $size => $cm)
                                <div class="rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-2.5 py-2">
                                    <p class="text-[10px] font-bold uppercase text-[#736A5B]">Tam {{ $size }}</p>
                                    @if ($cm !== null && $cm !== '')
                                        <p class="text-xs font-bold text-[#1C1915] mt-0.5">{{ $cm }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Materiais & Recursos --}}
                @if ($product->materials && count($product->materials) > 0)
                    <section class="pt-8 border-t border-[#F4F1EA]">
                        <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-[#1C1915] mb-4">Materiais & Composição</h2>
                        <ul class="flex flex-wrap gap-2.5">
                            @foreach ($product->materials as $material)
                                <li class="rounded-lg bg-[#FAF6F0] border border-[#E6E1D5] px-3.5 py-1.5 text-xs font-semibold text-[#544D42]">• {{ $material }}</li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                {{-- Cuidados --}}
                @if ($product->care_instructions && count($product->care_instructions) > 0)
                    <section class="pt-8 border-t border-[#F4F1EA]">
                        <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-[#1C1915] mb-4">Cuidados e Manutenção</h2>
                        <ul class="grid gap-3 sm:grid-cols-2 text-xs text-[#544D42]">
                            @foreach ($product->care_instructions as $care)
                                <li class="flex items-start gap-2">
                                    <span class="text-[#ff8400] font-bold">✓</span>
                                    <span>{{ $care }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>
        @endif
    </article>
</x-layouts.public>

