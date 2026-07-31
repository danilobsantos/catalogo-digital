<div x-data="{ tab: 'basic' }" class="flex flex-col gap-6">
    <header class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between border-b border-[#E6E1D5] pb-5">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">
                <a href="{{ route('admin.products.index') }}" class="hover:underline">Produtos</a> /
                {{ $product?->exists ? 'Editar' : 'Novo' }}
            </p>
            <h1 class="mt-1 text-2xl lg:text-3xl font-display font-bold tracking-tight text-[#1C1915]">
                {{ $product?->exists ?? false ? $product->code.' — '.$product->name : 'Novo produto' }}
            </h1>
        </div>
        <div class="flex items-center gap-2.5 self-start lg:self-auto">
            <a href="{{ route('admin.products.index') }}"
               class="rounded-full border border-[#E6E1D5] bg-white px-4 py-2 text-xs font-semibold text-[#544D42] hover:bg-[#FAFAF7] transition">
                Voltar
            </a>
            @if ($product?->exists)
                <a href="{{ route('public.products.show', $product->slug) }}" target="_blank"
                   class="rounded-full border border-[#ff8400] bg-[#FAF6F0] text-[#ff8400] px-4 py-2 text-xs font-bold hover:bg-[#ff8400] hover:text-white transition">
                    Ver público ↗
                </a>
            @endif
        </div>
    </header>

    @if (session('flash.success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-semibold text-emerald-800 flex items-center gap-2">
            <span>✓</span> {{ session('flash.success') }}
        </div>
    @endif

    <form wire:submit="save" class="grid gap-6 lg:grid-cols-3">
        {{-- Coluna principal --}}
        <section class="lg:col-span-2 rounded-2xl border border-[#E6E1D5] bg-white overflow-hidden shadow-xs">
            {{-- Tabs --}}
            <nav class="flex border-b border-[#E6E1D5] bg-[#F4F1EA] text-xs font-bold">
                @foreach (['basic' => 'Informações Básicas', 'specs' => 'Ficha Técnica', 'media' => 'Imagens & Capa'] as $key => $label)
                    <button type="button" @click="tab = '{{ $key }}'"
                            :class="tab === '{{ $key }}' ? 'bg-white text-[#ff8400] border-b-2 border-[#ff8400]' : 'text-[#736A5B] hover:text-[#1C1915] hover:bg-white/50'"
                            class="px-5 py-3.5 transition">
                        {{ $label }}
                    </button>
                @endforeach
            </nav>

            <div class="p-6 space-y-6">
                {{-- Tab: basic --}}
                <div x-show="tab === 'basic'" class="space-y-5">
                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Código *</label>
                            <input type="text" wire:model="code" required maxlength="32"
                                   class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                            @error('code') <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Variante</label>
                            <input type="text" wire:model="variant_code" maxlength="32"
                                   class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Slug URL</label>
                            <div class="mt-1.5 flex">
                                <input type="text" wire:model="slug" maxlength="200"
                                       class="flex-1 rounded-l-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                                <button type="button" wire:click="madeSlug"
                                        class="rounded-r-xl border border-l-0 border-[#E6E1D5] bg-[#F4F1EA] px-3 text-xs font-bold text-[#544D42] hover:bg-[#E6E1D5] transition">
                                    gerar
                                </button>
                            </div>
                            @error('slug') <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Nome do Produto *</label>
                        <input type="text" wire:model="name" required maxlength="180"
                               class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                        @error('name') <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Subtítulo</label>
                        <input type="text" wire:model="subtitle" maxlength="160"
                               class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Linha / Categoria</label>
                            <select wire:model="category_id" class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                                <option value="">— Nenhuma —</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Coleção</label>
                            <select wire:model="collection_id" class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                                <option value="">— Nenhuma —</option>
                                @foreach ($collections as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Marca Parceira</label>
                            <select wire:model="brand_id" class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                                <option value="">— Nenhuma —</option>
                                @foreach ($brands as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Descrição Curta (Resumo nos Cards)</label>
                        <textarea wire:model="short_description" maxlength="300" rows="2"
                                  class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]"></textarea>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Descrição Completa</label>
                        <textarea wire:model="description" rows="8"
                                  class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]"></textarea>
                    </div>
                </div>

                {{-- Tab: specs --}}
                <div x-show="tab === 'specs'" class="space-y-5" style="display: none;">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ([
                            ['sole', 'Solado'], ['leather', 'Couro'],
                            ['closure', 'Fechamento'], ['toe_cap', 'Biqueira / Bico'],
                            ['weight_grams', 'Peso Aprox. (g)'],
                        ] as [$field, $label])
                            <div>
                                <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">{{ $label }}</label>
                                <input type="text" wire:model="{{ $field }}" maxlength="80"
                                       class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                            </div>
                        @endforeach
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Aprovações / Nº C.A.</label>
                            <input type="text" wire:model="approvals" maxlength="160"
                                   class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Processo de Fabricação</label>
                            <input type="text" wire:model="manufacturing" maxlength="120"
                                   class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                        </div>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Materiais (separados por vírgula)</label>
                        <input type="text" wire:model="materialsCsv"
                               placeholder="COURO VAQUETA RELAX, ELASTICO COBERTO, PALMILHA ANTIBACTERIANA"
                               class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Cores Disponíveis (separadas por vírgula)</label>
                        <input type="text" wire:model="colorsCsv"
                               placeholder="PRETO, CAFÉ, TAN"
                               class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Cuidados e Conservação (um por linha)</label>
                        <textarea wire:model="careCsv" rows="6"
                                  class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]"></textarea>
                    </div>
                </div>

                {{-- Tab: media --}}
                <div x-show="tab === 'media'" class="space-y-5" style="display: none;">
                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Upload de Imagens (JPG/PNG/WebP — até 5MB)</label>
                        <input type="file" wire:model="newImages" multiple accept="image/*"
                               class="mt-2 block w-full text-xs text-[#544D42] file:mr-3 file:rounded-xl file:border-0 file:bg-[#ff8400] file:text-white file:px-4 file:py-2 file:font-bold hover:file:bg-[#A84D29] cursor-pointer">
                        @error('newImages.*') <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror

                        <div wire:loading wire:target="newImages" class="mt-2 text-xs font-bold text-[#ff8400]">Carregando imagens…</div>

                        @if (! empty($newImages))
                            <p class="mt-3 text-xs font-bold text-[#736A5B]">Pré-visualização do Envio:</p>
                            <div class="mt-2 grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @foreach ($newImages as $i => $img)
                                    <div class="aspect-square overflow-hidden rounded-xl bg-[#FAFAF7] border border-[#E6E1D5]">
                                        <img src="{{ $img->temporaryUrl() }}" alt="" class="size-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if ($product?->exists)
                        <div class="pt-4 border-t border-[#F4F1EA]">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[#1C1915]">Galeria Atual do Produto</h3>
                            @if ($product->images()->count() === 0)
                                <p class="mt-2 text-xs text-[#736A5B]">Nenhuma imagem cadastrada.</p>
                            @else
                                <div class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    @foreach ($product->images()->orderBy('sort_order')->get() as $image)
                                        <div class="space-y-1.5 bg-[#FAFAF7] border border-[#E6E1D5] p-2 rounded-xl">
                                            <div class="aspect-square overflow-hidden rounded-lg relative">
                                                <img src="{{ asset('storage/'.$image->path) }}" alt="" class="size-full object-cover">
                                                @if ($image->is_cover)
                                                    <span class="absolute top-1.5 left-1.5 rounded-md bg-[#D97706] px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-white">Capa</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-between text-[11px] pt-1">
                                                @if (! $image->is_cover)
                                                    <button type="button" wire:click="setAsCover({{ $image->id }})" class="font-bold text-[#ff8400] hover:underline">Marcar capa</button>
                                                @else
                                                    <span class="font-bold text-[#047857]">Capa Principal</span>
                                                @endif
                                                <button type="button" wire:click="deleteImage({{ $image->id }})" wire:confirm="Remover esta imagem?"
                                                        class="font-bold text-rose-600 hover:underline">Remover</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- Sidebar --}}
        <aside class="space-y-4">
            <div class="rounded-2xl border border-[#E6E1D5] bg-white p-5 shadow-xs">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#1C1915]">Opções & Atributos</h3>
                <div class="mt-4 space-y-3">
                    @foreach ([
                        ['is_active', 'Produto ativo no site'],
                        ['is_featured', 'Exibir em Destaques'],
                        ['is_new', 'Marcar como Novidade'],
                        ['is_bestseller', 'Marcar como Mais Vendido'],
                        ['has_ca', 'Possui C.A. de Segurança'],
                    ] as [$field, $label])
                        <label class="flex items-center gap-2.5 text-xs font-medium text-[#28231C] cursor-pointer">
                            <input type="checkbox" wire:model.live="{{ $field }}"
                                   class="rounded border-[#E6E1D5] text-[#ff8400] focus:ring-[#ff8400]">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @if ($has_ca)
                    <div class="mt-4 pt-3 border-t border-[#F4F1EA]">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-[#736A5B]">Número do C.A.</label>
                        <input type="text" wire:model="ca_number" maxlength="64" placeholder="Ex: 42.130"
                               class="mt-1.5 w-full rounded-xl border border-[#E6E1D5] bg-[#FAFAF7] px-3.5 py-2 text-xs font-medium text-[#1C1915] focus:border-[#ff8400]">
                    </div>
                @endif
            </div>

            <button type="submit" wire:loading.attr="disabled"
                    class="w-full rounded-full bg-[#ff8400] text-white px-5 py-3.5 text-xs font-bold uppercase tracking-wider hover:bg-[#A84D29] transition shadow-sm">
                <span wire:loading.remove>Salvar Produto</span>
                <span wire:loading>Salvando produto…</span>
            </button>

            @if ($product?->exists)
                <a href="{{ route('public.products.show', $product->slug) }}" target="_blank"
                   class="block text-center text-xs font-semibold text-[#736A5B] hover:text-[#ff8400] transition">
                    Ver no catálogo público ↗
                </a>
            @endif
        </aside>
    </form>
</div>

