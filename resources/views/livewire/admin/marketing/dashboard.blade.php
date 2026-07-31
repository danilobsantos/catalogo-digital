<div class="flex flex-col gap-6">
    <header class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between border-b border-[#E6E1D5] pb-5">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">Dashboard</p>
            <h1 class="mt-1 text-2xl lg:text-3xl font-display font-bold tracking-tight text-[#1C1915]">Métricas & Marketing</h1>
        </div>
        <div class="flex items-center gap-2">
            @foreach (['24h' => '24h', '7d' => '7 dias', '30d' => '30 dias', 'all' => 'Tudo'] as $value => $label)
                <button type="button" wire:click="$set('window', '{{ $value }}')"
                        class="@if ($window === $value) bg-[#ff8400] text-white shadow-xs @else bg-white text-[#544D42] border border-[#E6E1D5] hover:bg-[#FAFAF7] @endif px-3.5 py-1.5 rounded-full text-xs font-bold transition">
                    {{ $label }}
                </button>
            @endforeach
            <a href="{{ route('admin.marketing.export', ['window' => $window]) }}"
               class="ml-2 inline-flex items-center rounded-full border border-[#E6E1D5] bg-white px-4 py-1.5 text-xs font-semibold text-[#544D42] hover:bg-[#FAFAF7] transition">
                Exportar CSV ↗
            </a>
        </div>
    </header>

    {{-- KPI cards --}}
    <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-[#E6E1D5] bg-white p-5 shadow-xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Views (página produto)</p>
            <p class="mt-2 text-3xl font-display font-bold tracking-tight text-[#1C1915]">{{ number_format($kpi['views'] ?? 0, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-[#736A5B]">evento=view na janela</p>
        </div>
        <div class="rounded-2xl border border-[#E6E1D5] bg-white p-5 shadow-xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">WhatsApp Clicks</p>
            <p class="mt-2 text-3xl font-display font-bold tracking-tight text-[#1C1915]">{{ number_format($kpi['whatsapp_clicks'] ?? 0, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-[#736A5B]">evento=whatsapp_click</p>
        </div>
        <div class="rounded-2xl border border-[#E6E1D5] bg-white p-5 shadow-xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Buscas</p>
            <p class="mt-2 text-3xl font-display font-bold tracking-tight text-[#1C1915]">{{ number_format($kpi['searches'] ?? 0, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-[#736A5B]">consultas no catálogo</p>
        </div>
        <div class="rounded-2xl border border-[#E6E1D5] bg-white p-5 shadow-xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Banner Clicks</p>
            <p class="mt-2 text-3xl font-display font-bold tracking-tight text-[#1C1915]">{{ number_format($kpi['banner_clicks'] ?? 0, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-[#736A5B]">evento=banner_click</p>
        </div>
    </section>

    {{-- Top Search terms & viewed products --}}
    <section class="grid gap-4 lg:grid-cols-2">
        <div class="rounded-2xl border border-[#E6E1D5] bg-white overflow-hidden shadow-xs">
            <header class="px-5 py-3 border-b border-[#E6E1D5] bg-[#F4F1EA]">
                <h2 class="text-xs font-bold uppercase tracking-wider text-[#1C1915]">Termos Mais Buscados</h2>
            </header>
            <div class="divide-y divide-[#F4F1EA]">
                @if ($topSearches->isEmpty())
                    <p class="p-5 text-xs text-[#736A5B]">Sem buscas registradas no período.</p>
                @endif
                @foreach ($topSearches as $s)
                    <div class="flex items-center justify-between p-4 text-xs font-medium text-[#1C1915]">
                        <span class="truncate">{{ $s->term ?: '(vazio)' }}</span>
                        <span class="text-xs font-bold text-[#736A5B] bg-[#FAFAF7] px-2.5 py-1 rounded-full border border-[#E6E1D5]">{{ $s->c }}×</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl border border-[#E6E1D5] bg-white overflow-hidden shadow-xs">
            <header class="px-5 py-3 border-b border-[#E6E1D5] bg-[#F4F1EA]">
                <h2 class="text-xs font-bold uppercase tracking-wider text-[#1C1915]">Top Produtos Visualizados</h2>
            </header>
            <div class="divide-y divide-[#F4F1EA]">
                @forelse ($topViewed as $product)
                    <div class="flex items-center gap-3 p-4 text-xs">
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-[#1C1915] truncate">{{ $product->name }}</p>
                            <p class="text-[11px] text-[#736A5B]">{{ $product->code }}{{ $product->variant_code ? '-'.$product->variant_code : '' }} · {{ number_format($product->view_count, 0, ',', '.') }} views</p>
                        </div>
                        @php $cover = $product->images->firstWhere('is_cover', true) ?? $product->images->first(); @endphp
                        @if ($cover)
                            <img src="{{ asset('storage/'.($cover->thumb_path ?? $cover->path)) }}" alt="" class="size-9 rounded-lg object-cover border border-[#E6E1D5]">
                        @endif
                    </div>
                @empty
                    <p class="p-5 text-xs text-[#736A5B]">Sem produtos cadastrados.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- By day sparkline --}}
    <section class="rounded-2xl border border-[#E6E1D5] bg-white p-5 shadow-xs">
        <h2 class="text-xs font-bold uppercase tracking-wider text-[#1C1915]">Eventos por Dia</h2>
        <div class="mt-4 grid grid-cols-2 lg:grid-cols-7 gap-2 text-xs">
            @foreach ($byDay as $d)
                <div class="rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] p-3">
                    <p class="text-[11px] font-bold text-[#736A5B]">{{ \Carbon\Carbon::parse($d->day)->format('d/m') }}</p>
                    <p class="mt-1 font-bold text-sm text-[#1C1915]">{{ number_format($d->c, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-[#736A5B] mt-0.5">👁 {{ $d->views ?? 0 }} · 💬 {{ $d->clicks ?? 0 }}</p>
                </div>
            @endforeach
        </div>
    </section>
</div>
