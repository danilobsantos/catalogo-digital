<form wire:submit="save" class="max-w-3xl flex flex-col gap-6">
    <header class="border-b border-[#E6E1D5] pb-5">
        <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">
            <a href="{{ route('admin.collections.index') }}" class="hover:underline">Coleções</a> /
            {{ $collection?->exists ? 'Editar' : 'Nova' }}
        </p>
        <h1 class="mt-1 text-2xl lg:text-3xl font-display font-bold tracking-tight text-[#1C1915]">
            {{ $collection?->exists ? $collection->name : 'Nova coleção' }}
        </h1>
    </header>

    @if (session('flash.success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-semibold text-emerald-800 flex items-center gap-2">
            <span>✓</span> {{ session('flash.success') }}
        </div>
    @endif

    <section class="rounded-2xl border border-[#E6E1D5] bg-white p-6 space-y-5 shadow-xs">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="md:col-span-2">
                <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Nome *</label>
                <input type="text" wire:model="name" required maxlength="120"
                       class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400] transition">
                @error('name') <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Ordem</label>
                <input type="number" wire:model="sort_order"
                       class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400] transition">
            </div>
        </div>

        <div>
            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Slug URL</label>
            <div class="mt-1.5 flex">
                <input type="text" wire:model="slug" maxlength="160"
                       class="flex-1 rounded-l-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400] transition">
                <button type="button" wire:click="madeSlug"
                        class="rounded-r-xl border border-l-0 border-[#E6E1D5] bg-[#F4F1EA] px-3.5 text-xs font-bold text-[#544D42] hover:bg-[#E6E1D5] transition">
                    gerar
                </button>
            </div>
            @error('slug') <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Cor de destaque (Hex #RRGGBB)</label>
            <div class="flex items-center gap-3 mt-1.5">
                <input type="color" wire:model="accent_color"
                       class="h-10 w-16 rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] p-1 cursor-pointer">
                <span class="text-xs font-mono font-bold text-[#544D42]">{{ $accent_color ?: '#000000' }}</span>
            </div>
            @error('accent_color') <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Descrição</label>
            <textarea wire:model="description" rows="3"
                      class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400] transition"></textarea>
        </div>

        <div class="flex items-center gap-6 pt-2 border-t border-[#F4F1EA]">
            <label class="flex items-center gap-2.5 text-xs font-medium text-[#28231C] cursor-pointer">
                <input type="checkbox" wire:model="is_active" class="rounded border-[#E6E1D5] text-[#ff8400] focus:ring-[#ff8400]">
                <span>Ativa</span>
            </label>
            <label class="flex items-center gap-2.5 text-xs font-medium text-[#28231C] cursor-pointer">
                <input type="checkbox" wire:model="is_featured" class="rounded border-[#E6E1D5] text-[#ff8400] focus:ring-[#ff8400]">
                <span>Em destaque</span>
            </label>
        </div>
    </section>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.collections.index') }}"
           class="rounded-full border border-[#E6E1D5] bg-white px-5 py-2.5 text-xs font-semibold text-[#544D42] hover:bg-[#FAFAF7] transition">
            Voltar
        </a>
        <button type="submit" wire:loading.attr="disabled"
                class="rounded-full bg-[#ff8400] text-white px-6 py-2.5 text-xs font-bold uppercase tracking-wider hover:bg-[#A84D29] transition shadow-xs">
            <span wire:loading.remove>Salvar</span>
            <span wire:loading>Salvando…</span>
        </button>
    </div>
</form>
