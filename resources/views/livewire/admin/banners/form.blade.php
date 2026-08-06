<form wire:submit="save" class="max-w-3xl flex flex-col gap-6">
    <header class="border-b border-[#E6E1D5] pb-5">
        <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">
            <a href="{{ route('admin.banners.index') }}" class="hover:underline">Banners</a> /
            {{ $banner?->exists ? 'Editar' : 'Novo' }}
        </p>
        <h1 class="mt-1 text-2xl lg:text-3xl font-display font-bold tracking-tight text-[#1C1915]">
            {{ $banner?->exists ? $banner->title : 'Novo banner' }}
        </h1>
    </header>

    @if (session('flash.success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-semibold text-emerald-800 flex items-center gap-2">
            <span>✓</span> {{ session('flash.success') }}
        </div>
    @endif

    <section class="rounded-2xl border border-[#E6E1D5] bg-white p-6 space-y-5 shadow-xs">
        <div>
            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Título *</label>
            <input type="text" wire:model="title" required maxlength="180"
                   class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400] transition">
            @error('title') <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="md:col-span-2">
                <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Slug URL</label>
                <div class="mt-1.5 flex">
                    <input type="text" wire:model="slug" maxlength="180"
                           class="flex-1 rounded-l-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400] transition">
                    <button type="button" wire:click="madeSlug"
                            class="rounded-r-xl border border-l-0 border-[#E6E1D5] bg-[#F4F1EA] px-3.5 text-xs font-bold text-[#544D42] hover:bg-[#E6E1D5] transition">
                        gerar
                    </button>
                </div>
                @error('slug') <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Posição</label>
                <select wire:model="position"
                        class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400] transition">
                    <option value="hero">Hero Principal</option>
                    <option value="mid-1">Mid 1</option>
                    <option value="mid-2">Mid 2</option>
                    <option value="mid-3">Mid 3</option>
                    <option value="footer">Rodapé</option>
                </select>
            </div>
        </div>

        <div>
            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Subtítulo</label>
            <input type="text" wire:model="subtitle" maxlength="200"
                   class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400] transition">
        </div>

        <div>
            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Descrição</label>
            <textarea wire:model="description" rows="3"
                      class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400] transition"></textarea>
        </div>

        <div class="grid gap-4 md:grid-cols-1">
            <div>
                <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Alt da Imagem (SEO)</label>
                <input type="text" wire:model="image_alt" maxlength="160" placeholder="Ex: Banner coleção verão 2026"
                       class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400] transition">
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Texto Botão CTA</label>
                <input type="text" wire:model="cta_label" maxlength="64" placeholder="Ex: Ver Coleção"
                       class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400] transition">
            </div>
            <div>
                <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">URL do CTA</label>
                <input type="text" wire:model="cta_url" maxlength="255" placeholder="https://... ou /produtos"
                       class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400] transition">
            </div>
            <div>
                <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Rota do CTA</label>
                <input type="text" wire:model="cta_route_name" maxlength="160" placeholder="public.products.index"
                       class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400] transition">
            </div>
            <div class="md:col-span-2">
                <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Imagem do Banner</label>
                <div class="mt-1.5 flex flex-col gap-3">
                    @if ($newImage)
                        <div class="relative w-full overflow-hidden rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] p-2">
                            <img src="{{ $newImage->temporaryUrl() }}" class="h-40 w-full rounded-lg object-cover">
                            <button type="button" wire:click="$set('newImage', null)"
                                    class="absolute top-4 right-4 rounded-full bg-rose-600/90 text-white p-1.5 text-xs font-bold hover:bg-rose-700 transition shadow-xs"
                                    title="Remover nova imagem">
                                ✕
                            </button>
                        </div>
                    @elseif ($banner?->image_path)
                        <div class="relative w-full overflow-hidden rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] p-2">
                            <img src="{{ asset('storage/' . $banner->image_path) }}" class="h-40 w-full rounded-lg object-cover">
                            <div class="mt-2 flex items-center justify-between px-1 pb-1">
                                <span class="text-[10px] font-mono text-[#736A5B] truncate">{{ $banner->image_path }}</span>
                                <button type="button" wire:click="removeImage"
                                        class="text-xs font-bold text-rose-600 hover:underline">
                                    Remover imagem
                                </button>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-3">
                        <label class="inline-flex items-center gap-2 rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-4 py-2.5 text-xs font-semibold text-[#1C1915] cursor-pointer hover:bg-[#F4F1EA] transition">
                            <svg class="size-4 text-[#ff8400]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $newImage || $banner?->image_path ? 'Alterar Imagem...' : 'Selecionar Imagem...' }}</span>
                            <input type="file" wire:model="newImage" accept="image/jpeg,image/png,image/webp,image/avif" class="hidden">
                        </label>
                        <span wire:loading wire:target="newImage" class="text-xs text-[#ff8400] font-semibold animate-pulse">
                            Carregando preview...
                        </span>
                    </div>
                    @error('newImage') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Ordem</label>
                <input type="number" wire:model="sort_order"
                       class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400] transition">
            </div>
            <div>
                <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Início (Opcional)</label>
                <input type="datetime-local" wire:model="starts_at"
                       class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400] transition">
            </div>
            <div>
                <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Fim (Opcional)</label>
                <input type="datetime-local" wire:model="ends_at"
                       class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400] transition">
            </div>
        </div>

        <div class="pt-2 border-t border-[#F4F1EA]">
            <label class="flex items-center gap-2.5 text-xs font-medium text-[#28231C] cursor-pointer">
                <input type="checkbox" wire:model="is_active" class="rounded border-[#E6E1D5] text-[#ff8400] focus:ring-[#ff8400]">
                <span>Banner ativo no site</span>
            </label>
        </div>
    </section>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.banners.index') }}"
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
