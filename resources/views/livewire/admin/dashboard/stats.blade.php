<div class="flex flex-col gap-6">
    <header class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between border-b border-[#E6E1D5] pb-5">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">Visão Geral do Sistema</p>
            <h1 class="mt-1 text-2xl lg:text-3xl font-display font-bold tracking-tight text-[#1C1915]">Bem-vindo, {{ auth()->user()?->name }}.</h1>
        </div>
        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center gap-2 rounded-full bg-[#ff8400] text-white px-5 py-2.5 text-xs font-bold uppercase tracking-wider hover:bg-[#A84D29] transition shadow-xs self-start lg:self-auto">
            <span>+ Novo Produto</span>
        </a>
    </header>

    {{-- KPI cards --}}
    <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['Produtos Cadastrados', $kpi['products'], 'Total no catálogo', 'bg-[#FAFAF7]'],
            ['Produtos Ativos', $kpi['active_products'], 'Visíveis aos clientes', 'bg-emerald-50/50'],
            ['Com C.A. de Segurança', $kpi['ca_products'], 'Calçados profissionais', 'bg-[#FAF6F0]'],
            ['Novidades Registradas', $kpi['new_products'], 'Linha recente', 'bg-sky-50/50'],
            ['Categorias & Linhas', $kpi['categories'], 'Estrutura do menu', 'bg-[#FAFAF7]'],
            ['Coleções Especiais', $kpi['collections'], 'Destaques temáticos', 'bg-[#FAFAF7]'],
            ['Marcas Parceiras', $kpi['brands'], 'Fabricantes', 'bg-[#FAFAF7]'],
            ['Banners da Home', $kpi['banners'], 'Divulgação principal', 'bg-[#FAFAF7]'],
        ] as $card)
            <div class="rounded-2xl border border-[#E6E1D5] bg-white p-5 shadow-xs flex flex-col justify-between">
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">{{ $card[0] }}</p>
                <p class="mt-3 text-3xl font-display font-bold tracking-tight text-[#1C1915]">{{ number_format($card[1], 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-[#544D42] font-medium">{{ $card[2] }}</p>
            </div>
        @endforeach
    </section>

    {{-- Recent + Top Viewed --}}
    <section class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 rounded-2xl bg-white border border-[#E6E1D5] overflow-hidden shadow-xs">
            <header class="px-5 py-3.5 border-b border-[#E6E1D5] bg-[#F4F1EA] flex items-center justify-between">
                <h2 class="text-xs font-bold uppercase tracking-wider text-[#1C1915]">Produtos Atualizados Recentemente</h2>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-[#ff8400] hover:underline">Ver todos ↗</a>
            </header>
            <div class="divide-y divide-[#F4F1EA]">
                @forelse ($recent as $product)
                    <a href="{{ route('admin.products.edit', $product) }}" class="flex items-center gap-4 p-4 hover:bg-[#FAFAF7] transition group">
                        @php $cover = $product->images->firstWhere('is_cover', true) ?? $product->images->first(); @endphp
                        <div class="size-12 rounded-xl bg-[#F4F1EA] border border-[#E6E1D5] overflow-hidden shrink-0">
                            @if ($cover)
                                <img src="{{ asset('storage/'.$cover->path) }}" alt="" class="size-full object-cover">
                            @else
                                <div class="size-full flex items-center justify-center text-[#9E9585]">
                                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M2.25 21l8.012-9.738m0 0l3.738-4.333M10.262 11.262L21 2.25M10.262 11.262a3 3 0 1 1-4.244-4.243 3 3 0 0 1 4.244 4.243Z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-[#1C1915] truncate group-hover:text-[#ff8400] transition">{{ $product->name }}</p>
                            <p class="text-xs text-[#736A5B] font-mono">Cód: {{ $product->code }}{{ $product->variant_code ? '-'.$product->variant_code : '' }} · {{ $product->updated_at->diffForHumans() }}</p>
                        </div>
                        <span class="text-xs font-bold text-[#ff8400] group-hover:underline shrink-0">Editar →</span>
                    </a>
                @empty
                    <p class="p-5 text-xs text-[#736A5B]">Nenhum produto cadastrado ainda.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-[#E6E1D5] overflow-hidden shadow-xs">
            <header class="px-5 py-3.5 border-b border-[#E6E1D5] bg-[#F4F1EA]">
                <h2 class="text-xs font-bold uppercase tracking-wider text-[#1C1915]">Mais Visualizados</h2>
            </header>
            <ul class="divide-y divide-[#F4F1EA]">
                @forelse ($topViewed as $i => $product)
                    <li class="flex items-center gap-3.5 p-4 hover:bg-[#FAFAF7]">
                        <span class="size-7 rounded-lg bg-[#FAF6F0] border border-[#E6E1D5] grid place-items-center text-xs font-bold text-[#ff8400]">#{{ $i + 1 }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-[#1C1915] truncate">{{ $product->name }}</p>
                            <p class="text-[11px] font-semibold text-[#047857]">{{ number_format($product->view_count, 0, ',', '.') }} acessos</p>
                        </div>
                    </li>
                @empty
                    <li class="p-5 text-xs text-[#736A5B]">Sem dados de acessos ainda.</li>
                @endforelse
            </ul>
        </div>
    </section>

    {{-- Activity Log --}}
    <section class="rounded-2xl bg-white border border-[#E6E1D5] overflow-hidden shadow-xs">
        <header class="px-5 py-3.5 border-b border-[#E6E1D5] bg-[#F4F1EA]">
            <h2 class="text-xs font-bold uppercase tracking-wider text-[#1C1915]">Histórico de Atividades do Sistema</h2>
        </header>
        @if ($activities->isEmpty())
            <p class="px-5 py-6 text-xs text-[#736A5B]">Nenhuma atividade registrada até o momento.</p>
        @else
            <ul class="divide-y divide-[#F4F1EA]">
                @foreach ($activities as $a)
                    <li class="flex items-start gap-3.5 p-4 text-xs">
                        <span class="size-8 rounded-xl bg-[#FAFAF7] border border-[#E6E1D5] grid place-items-center text-[10px] uppercase font-bold text-[#544D42] shrink-0">
                            {{ mb_substr($a->log_name ?? 'log', 0, 4) }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-[#1C1915]">
                                {{ ucfirst($a->description) }}
                                @if ($a->subject)
                                    <span class="text-[#736A5B] font-normal">— {{ $a->subject_type }}{{ isset($a->subject->id) ? '#'.$a->subject->id : '' }}</span>
                                @endif
                            </p>
                            <p class="text-[11px] text-[#736A5B] mt-0.5">
                                {{ $a->causer?->name ?? 'Sistema' }} · {{ $a->created_at->diffForHumans() }}
                                @if ($a->event) · Evento: <span class="uppercase font-mono font-bold text-[#ff8400]">{{ $a->event }}</span> @endif
                            </p>
                        </div>
                        @if ($a->subject && method_exists($a->subject, 'getKey'))
                            <a href="{{ url('/admin/'.$a->log_name) }}"
                               class="text-xs font-bold text-[#ff8400] hover:underline shrink-0">Abrir →</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
</div>

