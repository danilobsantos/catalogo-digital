<div class="flex flex-col gap-6">
    <header class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between border-b border-[#E6E1D5] pb-5">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">Gestão de Catálogo</p>
            <h1 class="mt-1 text-2xl lg:text-3xl font-display font-bold tracking-tight text-[#1C1915]">Coleções Especiais</h1>
        </div>
        <a href="{{ route('admin.collections.create') }}"
           class="inline-flex items-center gap-2 rounded-full bg-[#ff8400] text-white px-5 py-2.5 text-xs font-bold uppercase tracking-wider hover:bg-[#A84D29] transition shadow-xs self-start lg:self-auto">
            <span>+ Nova Coleção</span>
        </a>
    </header>

    @if (session('flash.success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-semibold text-emerald-800 flex items-center gap-2">
            <span>✓</span> {{ session('flash.success') }}
        </div>
    @endif

    <section class="rounded-2xl bg-white border border-[#E6E1D5] overflow-hidden shadow-xs">
        <table class="w-full text-xs">
            <thead class="text-[11px] uppercase tracking-wider font-bold text-[#736A5B] bg-[#F4F1EA] border-b border-[#E6E1D5]">
                <tr>
                    <th class="text-left px-4 py-3.5">Coleção</th>
                    <th class="text-center px-4 py-3.5">Total de Produtos</th>
                    <th class="text-center px-4 py-3.5">Destaque</th>
                    <th class="text-center px-4 py-3.5">Cor de Acento</th>
                    <th class="text-right px-4 py-3.5">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F4F1EA]">
                @forelse ($collections as $c)
                    <tr class="hover:bg-[#FAFAF7] transition">
                        <td class="px-4 py-3.5">
                            <p class="font-bold text-sm text-[#1C1915]">{{ $c->name }}</p>
                            <p class="text-[11px] font-mono text-[#736A5B]">/{{ $c->slug }}</p>
                        </td>
                        <td class="px-4 py-3.5 text-center font-bold text-[#1C1915]">{{ $c->products_count }}</td>
                        <td class="px-4 py-3.5 text-center">{{ $c->is_featured ? '⭐ Sim' : '—' }}</td>
                        <td class="px-4 py-3.5 text-center"><span class="inline-block size-5 rounded-full border border-[#E6E1D5] shadow-2xs" style="background: {{ $c->accent_color ?? '#ff8400' }}"></span></td>
                        <td class="px-4 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.collections.edit', $c) }}" class="text-xs font-bold text-[#ff8400] hover:underline">Editar</a>
                                <button wire:click="delete({{ $c->id }})" wire:confirm="Remover?" class="text-xs font-bold text-rose-600 hover:underline">Remover</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-[#736A5B]">Nenhuma coleção cadastrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>

