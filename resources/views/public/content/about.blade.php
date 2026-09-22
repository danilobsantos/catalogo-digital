<x-layouts.public
    :title="'Quem Somos — '.config('catalog.company.name', 'CJ Calçados').' | Tradição e Qualidade'"
    description="Conheça a história da CJ Calçados em Guaxupé-MG. Há mais de 20 anos fabricando botinas de couro legítimo para segurança e passeio com alto conforto e resistência."
    image="{{ asset('images/sobre/producao-botinas-cj.jpg') }}"
>
    <x-slot:head>
        @php
            $schemaData = [
                '@context' => 'https://schema.org',
                '@type' => 'AboutPage',
                'name' => 'Quem Somos — '.config('catalog.company.name'),
                'description' => 'Há mais de 20 anos fabricando botinas de segurança e passeio em couro legítimo com alta resistência e conforto.',
                'mainEntity' => [
                    '@type' => 'Organization',
                    'name' => config('catalog.company.name'),
                    'slogan' => 'Qualidade que acompanha sua caminhada.',
                    'foundingDate' => '2004',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => 'Av. José Antônio dos Santos, 203',
                        'addressLocality' => 'Guaxupé',
                        'addressRegion' => 'MG',
                        'postalCode' => '37832-192',
                        'addressCountry' => 'BR',
                    ],
                    'image' => asset('images/sobre/fabrica-cj-calcados.jpg'),
                    'url' => url('/'),
                ],
            ];
        @endphp
        <script type="application/ld+json">
        {!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    </x-slot:head>

    {{-- Breadcrumb Padrão do Catálogo --}}
    <div class="container-app pt-6 pb-2">
        <nav class="text-xs font-semibold text-[#736A5B] flex items-center gap-1.5" aria-label="Navegação estrutural">
            <a href="{{ route('home') }}" class="hover:text-[#ff8400] transition">Início</a>
            <span class="text-[#9E9585]">/</span>
            <span class="text-[#1C1915] font-bold">Quem Somos</span>
        </nav>
    </div>

    {{-- Hero Banner Padronizado com o Card da Home --}}
    <section class="py-6 lg:py-10">
        <div class="container-app">
            <div class="rounded-3xl bg-[#F4F1EA] border border-[#E6E1D5] p-8 sm:p-12 lg:p-16">
                <div class="max-w-3xl space-y-4">
                    <span class="inline-block px-3 py-1 rounded-full bg-[#FAF6F0] text-[#ff8400] text-xs font-bold uppercase tracking-widest">
                        CJ Calçados • Desde 2004
                        <span class="sr-only">Sobre a CJ Calçados</span>
                    </span>
                    <h1 class="text-3xl sm:text-5xl font-display font-bold tracking-tight text-[#1C1915] leading-tight">
                        Mais de duas décadas transformando tradição e couro legítimo em calçados de alta resistência.
                    </h1>
                    <p class="text-base text-[#544D42] leading-relaxed">
                        A CJ nasceu da vivência de quem respira o setor calçadista há duas décadas. São mais de 20 anos fornecendo insumos de excelência para a indústria local e fabricando botinas que unem tecnologia, durabilidade e o verdadeiro conforto que o trabalhador brasileiro merece.
                    </p>
                    <p class="text-sm font-semibold text-[#ff8400] italic">
                        “Qualidade que acompanha sua caminhada.”
                    </p>
                    <div class="pt-4 flex flex-wrap gap-4">
                        <a href="{{ \App\Helpers\WhatsappLink::build('Olá! Gostaria de conversar com a equipe da CJ Calçados sobre os produtos e conhecer mais a fábrica.') }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 rounded-full bg-[#047857] text-white px-6 py-3.5 text-xs font-bold uppercase tracking-wider hover:bg-[#065F46] transition shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                            </svg>
                            <span>Conversar no WhatsApp</span>
                        </a>
                        <a href="{{ route('public.products.index') }}"
                           class="inline-flex items-center gap-2 rounded-full bg-white border border-[#E6E1D5] text-[#28231C] px-6 py-3.5 text-xs font-bold uppercase tracking-wider hover:bg-[#FAFAF7] transition">
                            <span>Ver Catálogo Completo</span>
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Métricas em Destaque (Cards Padronizados com Categories da Home) --}}
    <section class="container-app py-8 lg:py-12">
        <header class="flex items-end justify-between mb-8 pb-4 border-b border-[#E6E1D5]">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">Tradição Calçadista</p>
                <h2 class="text-2xl sm:text-3xl font-display font-bold tracking-tight text-[#1C1915] mt-1">Nossos Indicadores</h2>
            </div>
        </header>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($company['stats'] as $stat)
                <div class="rounded-2xl bg-white border border-[#E6E1D5] p-6 transition-all duration-300 hover:border-[#ff8400]/50 hover:shadow-md">
                    <span class="text-[11px] font-bold uppercase tracking-widest text-[#736A5B]">{{ $stat['unit'] ?? 'Destaque' }}</span>
                    <h3 class="mt-2 text-3xl font-display font-bold text-[#ff8400]">{{ $stat['value'] }}</h3>
                    <h4 class="mt-1 text-base font-display font-bold text-[#1C1915]">{{ $stat['label'] }}</h4>
                    <p class="mt-2 text-xs text-[#544D42] leading-relaxed">{{ $stat['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Seção Nossa História / Trajetória --}}
    <section class="container-app py-10 lg:py-14">
        <header class="flex items-end justify-between mb-8 pb-4 border-b border-[#E6E1D5]">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">Nossa História</p>
                <h2 class="text-2xl sm:text-3xl font-display font-bold tracking-tight text-[#1C1915] mt-1">Do fornecimento de insumos à fabricação própria</h2>
            </div>
        </header>

        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            {{-- Coluna de Texto --}}
            <div class="lg:col-span-6 space-y-4 text-sm sm:text-base text-[#544D42] leading-relaxed">
                <p>
                    A trajetória da <strong class="text-[#1C1915]">CJ Calçados</strong> é fundamentada na vivência prática de quem conhece cada etapa da cadeia produtiva. Foram mais de duas décadas atuando no fornecimento de insumos de ponta para a indústria local de calçados, construindo laços sólidos pautados em dedicação, respeito e transparência.
                </p>
                <p>
                    Com essa bagagem técnica e paixão pelo ofício, estruturamos nossa própria indústria para produzir botinas de segurança e passeio. Uma linha desenvolvida com rigor ergonômico, matérias-primas nobres e um olhar atento a cada detalhe.
                </p>
                <p>
                    Da criteriosa seleção do couro legítimo ao acabamento manual dos pespontos e vulcanização dos solados, nossa missão permanece inalterada: entregar calçados duráveis, seguros e extremamente confortáveis.
                </p>

                <div class="rounded-2xl bg-[#FAFAF7] border border-[#E6E1D5] p-5 flex items-start gap-4 mt-6">
                    <span class="inline-flex size-10 rounded-full bg-[#FAF6F0] text-[#ff8400] items-center justify-center shrink-0">
                        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </span>
                    <div>
                        <h4 class="font-display font-bold text-sm text-[#1C1915]">Compromisso com o trabalhador</h4>
                        <p class="text-xs text-[#736A5B] mt-0.5 leading-relaxed">Preservamos uma história construída com trabalho, respeito à mão de obra e orgulho de produzir no Brasil.</p>
                    </div>
                </div>
            </div>

            {{-- Coluna de Fotos com os cards padrão --}}
            <div class="lg:col-span-6 space-y-6">
                {{-- Foto 2: Esteira de Produção --}}
                <figure class="group relative overflow-hidden rounded-2xl bg-white border border-[#E6E1D5] transition-all duration-300 hover:border-[#ff8400]/50 hover:shadow-md">
                    <div class="aspect-[16/10] overflow-hidden bg-[#28231C]">
                        <img src="{{ asset('images/sobre/producao-botinas-cj.jpg') }}"
                             alt="Linha de montagem e produção de botinas de couro CJ Calçados"
                             class="size-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <figcaption class="p-4 bg-white flex items-center justify-between border-t border-[#E6E1D5]">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-[#736A5B]">Produção Ativa</span>
                            <p class="text-xs font-bold text-[#1C1915]">Montagem artesanal com fôrmas anatômicas</p>
                        </div>
                        <span class="inline-block px-2.5 py-0.5 rounded-full bg-[#E8F5E9] text-[#047857] text-[11px] font-bold uppercase tracking-wider">
                            Fábrica
                        </span>
                    </figcaption>
                </figure>

                {{-- Foto 1: Fachada da Fábrica --}}
                <figure class="group relative overflow-hidden rounded-2xl bg-white border border-[#E6E1D5] transition-all duration-300 hover:border-[#ff8400]/50 hover:shadow-md">
                    <div class="aspect-[16/9] overflow-hidden bg-[#28231C]">
                        <img src="{{ asset('images/sobre/fabrica-cj-calcados.jpg') }}"
                             alt="Estrutura e fachada da fábrica CJ Calçados em Guaxupé - MG"
                             class="size-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <figcaption class="p-4 bg-white flex items-center justify-between border-t border-[#E6E1D5]">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-[#736A5B]">Sede Fabril</span>
                            <p class="text-xs font-bold text-[#1C1915]">Guaxupé — Sul de Minas Gerais</p>
                        </div>
                        <a href="{{ $company['address']['maps_url'] }}"
                           target="_blank" rel="noopener"
                           class="text-xs font-bold text-[#ff8400] hover:underline uppercase tracking-wider">
                            Ver no Maps →
                        </a>
                    </figcaption>
                </figure>
            </div>
        </div>
    </section>

    {{-- Os 5 Pilares Fundamentais --}}
    <section class="container-app py-10 lg:py-14">
        <header class="flex items-end justify-between mb-8 pb-4 border-b border-[#E6E1D5]">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">Fundamentos</p>
                <h2 class="text-2xl sm:text-3xl font-display font-bold tracking-tight text-[#1C1915] mt-1">Os 5 Pilares de Excelência</h2>
            </div>
        </header>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            @foreach ($company['pillars'] as $index => $pillar)
                <div class="rounded-2xl bg-white border border-[#E6E1D5] p-6 transition-all duration-300 hover:border-[#ff8400]/50 hover:shadow-md">
                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-[#FAF6F0] text-[#ff8400] text-[10px] font-bold uppercase tracking-widest">
                        Pilar 0{{ $index + 1 }}
                    </span>
                    <h3 class="mt-3 text-lg font-display font-bold text-[#1C1915]">{{ $pillar['title'] }}</h3>
                    <p class="mt-2 text-xs text-[#544D42] leading-relaxed">{{ $pillar['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Por que escolher a CJ Calçados? --}}
    <section class="container-app py-10 lg:py-14">
        <header class="flex items-end justify-between mb-8 pb-4 border-b border-[#E6E1D5]">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">Diferenciais</p>
                <h2 class="text-2xl sm:text-3xl font-display font-bold tracking-tight text-[#1C1915] mt-1">Por que escolher a CJ Calçados?</h2>
            </div>
        </header>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($company['differentials'] as $diff)
                <div class="group rounded-2xl bg-white border border-[#E6E1D5] p-6 transition-all duration-300 hover:border-[#ff8400]/50 hover:shadow-md">
                    <h3 class="text-base font-display font-bold text-[#1C1915] group-hover:text-[#ff8400] transition">
                        {{ $diff['title'] }}
                    </h3>
                    <p class="mt-2 text-xs text-[#544D42] leading-relaxed">
                        {{ $diff['desc'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Card de Localização / Visita Fabril --}}
    <section class="container-app py-10 lg:py-14">
        <div class="rounded-3xl bg-[#F4F1EA] border border-[#E6E1D5] p-8 sm:p-12 lg:p-14">
            <div class="grid lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-7 space-y-3">
                    <span class="inline-block px-3 py-1 rounded-full bg-[#FAF6F0] text-[#ff8400] text-xs font-bold uppercase tracking-widest">
                        Estrutura Fabril Própria
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-display font-bold tracking-tight text-[#1C1915]">
                        Venha tomar um café ou agendar uma cotação direta na fábrica
                    </h2>
                    <p class="text-sm text-[#544D42] leading-relaxed">
                        Localizada estrategicamente em Guaxupé/MG, nossa fábrica está estruturada para atender pronta-entrega e encomendas para empresas, cooperativas e revendedores de todo o país.
                    </p>
                    <p class="text-xs sm:text-sm font-semibold text-[#1C1915] pt-2">
                        <span class="text-[#736A5B]">Endereço:</span> {{ $company['address']['full'] }}
                    </p>
                </div>
                <div class="lg:col-span-5 flex flex-col sm:items-end gap-3">
                    <a href="{{ $company['address']['maps_url'] }}"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center justify-center gap-2 rounded-full bg-[#1C1915] text-[#FAFAF7] hover:bg-[#ff8400] px-6 py-3.5 text-xs font-bold uppercase tracking-wider transition shadow-md w-full sm:w-72">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                        <span>Abrir no Google Maps</span>
                    </a>
                    <a href="{{ \App\Helpers\WhatsappLink::build('Olá! Gostaria de informações comerciais e cotações para minha empresa/loja.') }}"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center justify-center gap-2 rounded-full bg-[#047857] text-white hover:bg-[#065F46] px-6 py-3.5 text-xs font-bold uppercase tracking-wider transition shadow-md w-full sm:w-72">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                            <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                        </svg>
                        <span>Atendimento Comercial</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Banner CTA WhatsApp Final (Padrão Home) --}}
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
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('public.products.index') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-[#ff8400] text-white px-8 py-4 text-sm font-bold uppercase tracking-wider hover:bg-[#A84D29] transition shadow-md">
                    <span>Ver Catálogo Completo</span>
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ \App\Helpers\WhatsappLink::build('Olá! Gostaria de falar com o atendimento da CJ Calçados.') }}"
                   target="_blank" rel="noopener"
                   class="inline-flex items-center gap-3 rounded-full bg-[#047857] px-8 py-4 text-sm font-bold uppercase tracking-wider text-white hover:bg-[#065F46] transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                        <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                    </svg>
                    <span>Falar com Consultor</span>
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
