<div class="flex flex-col gap-6">
    <header class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between border-b border-[#E6E1D5] pb-5">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">Gestão de Catálogo</p>
            <h1 class="mt-1 text-2xl lg:text-3xl font-display font-bold tracking-tight text-[#1C1915]">Produtos Cadastrados</h1>
            <p class="mt-1 text-xs text-[#544D42]">Encontrado(s) <strong class="text-[#1C1915] font-bold">{{ $products->total() }}</strong> produto(s) no sistema.</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center gap-2 rounded-full bg-[#ff8400] text-white px-5 py-2.5 text-xs font-bold uppercase tracking-wider hover:bg-[#A84D29] transition shadow-xs self-start lg:self-auto">
            <span>+ Novo Produto</span>
        </a>
    </header>

    @if (session('flash.success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-semibold text-emerald-800 flex items-center gap-2">
            <span>✓</span> {{ session('flash.success') }}
        </div>
    @endif

    {{-- Painel de Filtros --}}
    <section class="grid gap-4 md:grid-cols-2 lg:grid-cols-5 rounded-2xl bg-white border border-[#E6E1D5] p-5 shadow-xs">
        <div>
            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Buscar Produto</label>
            <input type="search" wire:model.live.debounce.300ms="search"
                   placeholder="Nome, código ou slug…"
                   class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] placeholder-[#9E9585] focus:border-[#ff8400] focus:ring-1 focus:ring-[#ff8400]">
        </div>
        <div>
            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Linha / Categoria</label>
            <select wire:model.live="categoryId"
                    class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                <option value="">Todas as linhas</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Coleção</label>
            <select wire:model.live="collectionId"
                    class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                <option value="">Todas as coleções</option>
                @foreach ($collections as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Filtrar C.A.</label>
            <select wire:model.live="onlyCa"
                    class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                <option value="">Todos</option>
                <option value="1">Somente com C.A.</option>
                <option value="0">Sem C.A.</option>
            </select>
        </div>
        <div>
            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Status de Exibição</label>
            <select wire:model.live="onlyActive"
                    class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                <option value="">Todos</option>
                <option value="1">Ativos</option>
                <option value="0">Inativos</option>
            </select>
        </div>
    </section>

    {{-- Tabela de Produtos --}}
    <section class="rounded-2xl bg-white border border-[#E6E1D5] overflow-hidden shadow-xs">
        <table class="w-full text-xs">
            <thead class="text-[11px] uppercase tracking-wider font-bold text-[#736A5B] bg-[#F4F1EA] border-b border-[#E6E1D5]">
                <tr>
                    <th class="text-left px-4 py-3.5">Produto</th>
                    <th class="text-left px-4 py-3.5">Linha / Categoria</th>
                    <th class="text-left px-4 py-3.5">Coleção</th>
                    <th class="text-center px-4 py-3.5">C.A.</th>
                    <th class="text-center px-4 py-3.5">Status</th>
                    <th class="text-right px-4 py-3.5">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F4F1EA]">
                @forelse ($products as $product)
                    @php $cover = $product->images->firstWhere('is_cover', true) ?? $product->images->first(); @endphp
                    <tr wire:key="product-{{ $product->id }}" class="hover:bg-[#FAFAF7] transition">
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="size-12 shrink-0 rounded-xl overflow-hidden bg-[#F4F1EA] border border-[#E6E1D5]">
                                    @if ($cover)
                                        <img src="{{ asset('storage/'.$cover->path) }}" alt="" class="size-full object-cover">
                                    @else
                                        <div class="size-full flex items-center justify-center text-[#9E9585]">
                                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M2.25 21l8.012-9.738m0 0l3.738-4.333M10.262 11.262L21 2.25M10.262 11.262a3 3 0 1 1-4.244-4.243 3 3 0 0 1 4.244 4.243Z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-sm text-[#1C1915] truncate">{{ $product->name }}</p>
                                    <p class="text-[11px] font-mono text-[#736A5B]">Cód: {{ $product->code }}{{ $product->variant_code ? '-'.$product->variant_code : '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 font-semibold text-[#3D372E]">{{ $product->category->name ?? '—' }}</td>
                        <td class="px-4 py-3.5 font-medium text-[#544D42]">{{ $product->collection->name ?? '—' }}</td>
                        <td class="px-4 py-3.5 text-center">
                            @if ($product->has_ca)
                                <span class="inline-block rounded-md bg-[#FAF6F0] border border-[#D97706]/30 text-[#D97706] px-2 py-0.5 text-[10px] font-bold">Sim</span>
                            @else
                                <span class="text-[#9E9585] text-[11px]">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if ($product->is_active)
                                <span class="inline-block rounded-full bg-emerald-50 border border-emerald-200 text-[#047857] px-2.5 py-0.5 text-[10px] font-bold">Ativo</span>
                            @else
                                <span class="inline-block rounded-full bg-neutral-100 border border-neutral-200 text-[#736A5B] px-2.5 py-0.5 text-[10px] font-semibold">Inativo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('public.products.show', $product->slug) }}" target="_blank"
                                   class="text-xs font-semibold text-[#736A5B] hover:text-[#1C1915] transition">Ver ↗</a>
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="text-xs font-bold text-[#ff8400] hover:underline">Editar</a>
                                <button wire:click="delete({{ $product->id }})" wire:confirm="Tem certeza que deseja remover este produto?"
                                        class="text-xs font-bold text-rose-600 hover:underline">
                                    Remover
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-[#736A5B]">
                            Nenhum produto encontrado com os filtros selecionados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-[#E6E1D5] bg-[#FAFAF7]">
            {{ $products->links() }}
        </div>
    </section>
</div>

