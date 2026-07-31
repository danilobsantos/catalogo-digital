@props(['product'])

@php
    $cover = $product->images->firstWhere('is_cover', true) ?? $product->images->first();
    $href = route('public.products.show', $product->slug);
    $whatsapp = \App\Helpers\WhatsappLink::build(
        config('catalog.whatsapp.message'),
        ['produto' => $product->name, 'codigo' => trim($product->code.(isset($product->variant_code) ? '-'.$product->variant_code : ''))]
    );
@endphp

<article {{ $attributes->merge(['class' => 'group relative flex flex-col overflow-hidden rounded-2xl bg-white border border-[#E6E1D5] hover:border-[#ff8400]/50 hover:shadow-lg transition-all duration-300']) }}>
    <a href="{{ $href }}" class="block">
        <div class="aspect-square bg-[#F4F1EA] overflow-hidden relative">
            @if ($cover)
                <picture class="block w-full h-full">
                    @if ($cover->thumb_path)
                        <source srcset="{{ asset('storage/'.$cover->thumb_path) }}" type="image/webp">
                    @endif
                    @if ($cover->cover_path)
                        <source srcset="{{ asset('storage/'.$cover->cover_path) }}" type="image/webp">
                    @endif
                    <img src="{{ asset('storage/'.$cover->path) }}" alt="{{ $cover->alt_text ?? $product->name }}"
                         loading="lazy" decoding="async"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out">
                </picture>
            @else
                <div class="w-full h-full flex flex-col items-center justify-center text-[#9E9585] p-4 text-center">
                    <svg class="size-10 mb-2 opacity-60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M2.25 21l8.012-9.738m0 0l3.738-4.333M10.262 11.262L21 2.25M10.262 11.262a3 3 0 1 1-4.244-4.243 3 3 0 0 1 4.244 4.243Z"/>
                    </svg>
                    <span class="text-xs font-medium">Foto em breve</span>
                </div>
            @endif

            {{-- Badges --}}
            <div class="absolute top-3 left-3 flex flex-col gap-1.5 z-10">
                @if ($product->is_new)
                    <span class="rounded-full bg-[#c25e38] px-2.5 py-0.5 text-[10px] uppercase tracking-wider font-bold text-white shadow-sm">
                        Novo
                    </span>
                @endif
                @if ($product->has_ca)
                    <span class="rounded-full bg-[#544D42] px-2.5 py-0.5 text-[10px] uppercase tracking-wider font-bold text-white shadow-sm">
                        C.A.
                    </span>
                @endif
            </div>
        </div>

        <div class="p-5 pb-0">
            <p class="text-[11px] uppercase tracking-[0.2em] font-semibold text-[#ff8400]">
                Cód: {{ $product->code }}{{ $product->variant_code ? '-'.$product->variant_code : '' }}
                @if ($product->leather) · {{ $product->leather }} @endif
            </p>
            <h3 class="mt-1.5 font-display font-bold text-base leading-snug line-clamp-2 text-[#1C1915] group-hover:text-[#ff8400] transition">
                {{ $product->name }}
            </h3>
        </div>
    </a>

    <div class="p-5 mt-auto">
        <a href="{{ $whatsapp }}" target="_blank" rel="noopener"
           class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#047857] hover:bg-[#065F46] text-white py-3 text-xs font-bold uppercase tracking-wider transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
            </svg>
            Orçamento
        </a>
    </div>
</article>

