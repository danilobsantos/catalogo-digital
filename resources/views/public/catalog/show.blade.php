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
                        @php
                            $caNum = trim((string) ($product->ca_number ?: (is_numeric($product->approvals) ? $product->approvals : '')));
                        @endphp
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[#FAF6F0] border border-[#D97706]/30 text-[#D97706] px-3.5 py-1 text-xs font-bold">
                            <svg class="size-3.5 fill-current" viewBox="0 0 20 20"><path d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm-1-11a1 1 0 1 1 2 0v3a1 1 0 1 1-2 0V7Zm1 8a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/></svg>
                            @if ($caNum !== '')
                                C.A. {{ $caNum }}
                            @else
                                Certificado de Aprovação (CA)
                            @endif
                        </span>
                    @endif
                    @if ($product->is_new)
                        <span class="rounded-full bg-[#c25e38] text-white px-3.5 py-1 text-xs font-bold uppercase tracking-wider">Lançamento</span>
                    @endif
                    @if (\App\Helpers\EmbroideryHelper::hasEmbroidery($product))
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[#FAF6F0] border border-[#E6E1D5] text-[#736A5B] px-3.5 py-1 text-xs font-bold">
                            <span class="text-sm"></span>Bordado
                        </span>
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
                            'Cores Disponíveis' => ($product->colors && count($product->colors) > 0) ? implode(', ', $product->colors) : null,
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

        {{-- Detalhes Adicionais (Descrição, Cores/Swatches, Medidas, Materiais e Cuidados) --}}
        @if ($product->description || ($product->colors && count($product->colors) > 0) || ($product->size_chart && count($product->size_chart) > 0) || ($product->materials && count($product->materials) > 0) || ($product->care_instructions && count($product->care_instructions) > 0))
            <div class="mt-8 lg:mt-12 bg-white rounded-3xl border border-[#E6E1D5] p-6 sm:p-10 shadow-sm space-y-10">
                {{-- Descrição detalhada --}}
                @if ($product->description)
                    <section>
                        <h2 class="text-sm sm:text-base font-bold uppercase tracking-wider text-[#1C1915] mb-3">Descrição do Modelo</h2>
                        <p class="text-xs sm:text-sm text-[#544D42] leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
                    </section>
                @endif

                {{-- Cores e Variações de Couro Disponíveis --}}
                @php
                    $leatherGroups = \App\Helpers\LeatherSwatchHelper::getGroupedSwatches($product->leather, $product->colors);
                @endphp
                @if (count($leatherGroups) > 0)
                    <section class="pt-8 border-t border-[#F4F1EA]" x-data="{ modalOpen: false, modalTitle: '', modalImage: '', modalHex: '#736a5b' }">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
                            <div>
                                <h2 class="text-sm sm:text-base font-bold uppercase tracking-wider text-[#1C1915]">Cores & Couros Disponíveis</h2>
                                <p class="text-xs text-[#736A5B] mt-0.5">Mostruário oficial de tonalidades e texturas reais para este modelo</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            @foreach ($leatherGroups as $groupName => $swatches)
                                <div class="bg-[#FAFAF7] rounded-3xl p-5 sm:p-7 border border-[#E6E1D5]">
                                    <div class="flex items-center gap-2.5 mb-6 pb-4 border-b border-[#E6E1D5]">
                                        <span class="size-2.5 rounded-full bg-[#ff8400]"></span>
                                        <h3 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-[#1C1915]">
                                            Couro {{ $groupName }}
                                        </h3>
                                        <span class="text-[10px] font-semibold text-[#736A5B] bg-white px-2.5 py-0.5 rounded-full border border-[#E6E1D5] ml-auto">
                                            {{ count($swatches) }} {{ count($swatches) === 1 ? 'opção' : 'opções' }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-5">
                                        @foreach ($swatches as $swatch)
                                            <div class="group relative rounded-2xl border border-[#E6E1D5] bg-white p-3 transition-all duration-200 hover:border-[#ff8400] hover:shadow-md cursor-pointer flex flex-col justify-between"
                                                 @click="modalTitle = '{{ $swatch['name'] }} ({{ $swatch['leather'] }})'; modalImage = '{{ $swatch['image_url'] ?? '' }}'; modalHex = '{{ $swatch['hex'] }}'; modalOpen = true">
                                                <div class="aspect-square w-full overflow-hidden rounded-xl bg-[#FAF6F0] border border-[#E6E1D5] relative flex items-center justify-center">
                                                    @if ($swatch['image_url'])
                                                        <img src="{{ $swatch['image_url'] }}"
                                                             alt="Amostra de couro {{ $swatch['name'] }}"
                                                             loading="lazy"
                                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/15 transition-colors flex items-center justify-center">
                                                            <span class="opacity-0 group-hover:opacity-100 transition-opacity bg-white/95 text-[#1C1915] text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm flex items-center gap-1">
                                                                <svg class="size-3 text-[#ff8400]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                                                Ver textura
                                                            </span>
                                                        </div>
                                                    @else
                                                        <div class="size-12 rounded-full border border-black/10 shadow-xs" style="background-color: {{ $swatch['hex'] }}"></div>
                                                    @endif
                                                </div>
                                                <div class="mt-2.5 text-center">
                                                    <p class="text-xs font-bold text-[#1C1915] group-hover:text-[#ff8400] transition-colors">{{ $swatch['name'] }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Modal Lightbox para visualização da textura em alta definição --}}
                        <div x-show="modalOpen"
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             @keydown.escape.window="modalOpen = false"
                             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-black/80 backdrop-blur-sm">
                            <div class="relative max-w-md sm:max-w-xl md:max-w-2xl w-full bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-[#E6E1D5] max-h-[94vh] flex flex-col"
                                 @click.outside="modalOpen = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="scale-95 opacity-0"
                                 x-transition:enter-end="scale-100 opacity-100">
                                <div class="flex items-center justify-between pb-3.5 border-b border-[#F4F1EA]">
                                    <div>
                                        <p class="text-[10px] sm:text-xs uppercase tracking-wider font-bold text-[#ff8400]">Amostra Real</p>
                                        <h3 class="font-display font-bold text-lg sm:text-2xl text-[#1C1915] mt-0.5" x-text="modalTitle"></h3>
                                    </div>
                                    <button type="button" @click="modalOpen = false" class="size-9 rounded-full bg-[#F4F1EA] text-[#544D42] hover:bg-[#E6E1D5] flex items-center justify-center font-bold text-sm transition-colors cursor-pointer">
                                        ✕
                                    </button>
                                </div>
                                <div class="mt-4 sm:mt-5 aspect-square rounded-2xl overflow-hidden bg-[#FAFAF7] border border-[#E6E1D5] flex items-center justify-center flex-1 max-h-[68vh]">
                                    <template x-if="modalImage">
                                        <img :src="modalImage" :alt="modalTitle" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!modalImage">
                                        <div class="size-36 sm:size-48 rounded-full border border-black/10 shadow-sm" :style="'background-color: ' + modalHex"></div>
                                    </template>
                                </div>
                                <p class="mt-3.5 text-center text-xs sm:text-sm text-[#736A5B]">Tonalidade e textura real do couro utilizado na fabricação</p>
                            </div>
                        </div>
                    </section>
                @endif

                {{-- Opções de Bordados Disponíveis --}}
                @php
                    $embroideryOptions = \App\Helpers\EmbroideryHelper::getOptionsForProduct($product);
                @endphp
                @if (count($embroideryOptions) > 0)
                    <section class="pt-8 border-t border-[#F4F1EA]" x-data="{ modalOpen: false, modalTitle: '', modalSubtitle: '', modalImage: '' }">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
                            <div>
                                <h2 class="text-sm sm:text-base font-bold uppercase tracking-wider text-[#1C1915]">Opções de Bordados Disponíveis</h2>
                                <p class="text-xs text-[#736A5B] mt-0.5">Modelos e desenhos de bordados disponíveis para personalização deste calçado</p>
                            </div>
                        </div>

                        <div class="bg-[#FAFAF7] rounded-3xl p-5 sm:p-7 border border-[#E6E1D5]">
                            <div class="flex items-center gap-2.5 mb-6 pb-4 border-b border-[#E6E1D5]">
                                <span class="size-2.5 rounded-full bg-[#ff8400]"></span>
                                <h3 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-[#1C1915]">
                                    Desenhos & Pespontos
                                </h3>
                                <span class="text-[10px] font-semibold text-[#736A5B] bg-white px-2.5 py-0.5 rounded-full border border-[#E6E1D5] ml-auto">
                                    {{ count($embroideryOptions) }} {{ count($embroideryOptions) === 1 ? 'opção' : 'opções' }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-5">
                                @foreach ($embroideryOptions as $emb)
                                    <div class="group relative rounded-2xl border border-[#E6E1D5] bg-white p-3 transition-all duration-200 hover:border-[#ff8400] hover:shadow-md cursor-pointer flex flex-col justify-between"
                                         @click="modalOpen = true; modalTitle = '{{ $emb['title'] }}'; modalSubtitle = '{{ $emb['subtitle'] }}'; modalImage = '{{ $emb['image_url'] }}'">
                                        <div class="aspect-square w-full overflow-hidden rounded-xl bg-[#FAF6F0] border border-[#E6E1D5] relative flex items-center justify-center">
                                            <img src="{{ $emb['image_url'] }}"
                                                 alt="{{ $emb['title'] }}"
                                                 loading="lazy"
                                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/15 transition-colors flex items-center justify-center">
                                                <span class="opacity-0 group-hover:opacity-100 transition-opacity bg-white/95 text-[#1C1915] text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm flex items-center gap-1">
                                                    <svg class="size-3 text-[#ff8400]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                                    Ver detalhe
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-2.5 text-center">
                                            <p class="text-xs font-bold text-[#1C1915] group-hover:text-[#ff8400] transition-colors">{{ $emb['title'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Modal Lightbox de Bordado --}}
                        <div x-show="modalOpen"
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             @keydown.escape.window="modalOpen = false"
                             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-black/80 backdrop-blur-sm">
                            <div class="relative max-w-md sm:max-w-xl md:max-w-2xl w-full bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-[#E6E1D5] max-h-[94vh] flex flex-col"
                                 @click.outside="modalOpen = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="scale-95 opacity-0"
                                 x-transition:enter-end="scale-100 opacity-100">
                                <div class="flex items-center justify-between pb-3.5 border-b border-[#F4F1EA]">
                                    <div>
                                        <p class="text-[10px] sm:text-xs uppercase tracking-wider font-bold text-[#ff8400]">Personalização</p>
                                        <h3 class="font-display font-bold text-lg sm:text-2xl text-[#1C1915] mt-0.5" x-text="modalTitle"></h3>
                                        <p class="text-xs sm:text-sm text-[#736A5B] mt-0.5" x-text="modalSubtitle"></p>
                                    </div>
                                    <button type="button" @click="modalOpen = false" class="size-9 rounded-full bg-[#F4F1EA] text-[#544D42] hover:bg-[#E6E1D5] flex items-center justify-center font-bold text-sm transition-colors cursor-pointer">
                                        ✕
                                    </button>
                                </div>
                                <div class="mt-4 sm:mt-5 aspect-square rounded-2xl overflow-hidden bg-[#FAFAF7] border border-[#E6E1D5] flex items-center justify-center flex-1 max-h-[68vh]">
                                    <img :src="modalImage" :alt="modalTitle" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-3.5 text-center text-xs sm:text-sm text-[#736A5B]">Detalhe da costura e acabamento do bordado no cano do calçado</p>
                            </div>
                        </div>
                    </section>
                @endif

                {{-- Tabela de medidas --}}
                @if ($product->size_chart && count($product->size_chart) > 0)
                    <section class="pt-8 border-t border-[#F4F1EA]">
                        <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-1 mb-4">
                            <h2 class="text-sm sm:text-base font-bold uppercase tracking-wider text-[#1C1915]">Tabela de Numeração & Medidas (cm)</h2>
                            <p class="text-xs text-[#736A5B] font-medium">* Medidas em centímetros (cm) referentes ao comprimento da sola do calçado.</p>
                        </div>
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

