<header x-data="{ mobileOpen: false, searchOpen: false }" class="sticky top-0 z-40 w-full bg-[#FAFAF7]/95 backdrop-blur-md border-b border-[#E6E1D5]">
    {{-- Top Announcement Bar --}}
    <div class="bg-[#28231C] text-[#FAFAF7] text-xs py-2 px-4">
        <div class="container-app flex items-center justify-between">
            <p class="flex items-center gap-2 font-medium">
                <span class="inline-block size-2 rounded-full bg-[#D97706]"></span>
                Catálogo Oficial CJ Calçados — Botinas e Calçados de Couro Premium
            </p>
            <a href="{{ \App\Helpers\WhatsappLink::build(config('catalog.whatsapp.message'), ['produto' => 'Catálogo', 'codigo' => 'geral']) }}"
               target="_blank" rel="noopener"
               class="hidden sm:inline-flex items-center gap-1.5 hover:text-[#D97706] transition text-[11px] uppercase tracking-wider font-semibold">
                <svg class="size-3.5 fill-current text-emerald-400" viewBox="0 0 24 24"><path d="M19.05 4.91A10 10 0 0 0 4.18 18.85L3 22l3.32-1.13A10 10 0 1 0 19.05 4.91Z"/></svg>
                Atendimento Direto WhatsApp
            </a>
        </div>
    </div>

    {{-- Main Navbar --}}
    <div class="container-app flex items-center justify-between h-20">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
            <img src="{{ asset('logo-cj-calcados.png') }}" alt="CJ Calçados" class="size-12 object-contain transition group-hover:scale-105">
            <div class="flex flex-col">
                <span class="font-display font-bold text-2xl tracking-tight text-[#1C1915]">C & J</span>
                <span class="text-[11px] uppercase tracking-[0.15em] text-[#736A5B] font-medium">INDÚSTRIA DE CALÇADOS</span>
            </div>
        </a>

        {{-- Desktop Navigation Links --}}
        <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-[#3D372E]">
            <a href="{{ route('home') }}"
               class="hover:text-[#ff8400] transition relative py-2 {{ request()->routeIs('home') ? 'text-[#ff8400] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:bg-[#ff8400]' : '' }}">
                Início
            </a>
            <a href="{{ route('public.products.index') }}"
               class="hover:text-[#ff8400] transition relative py-2 {{ request()->routeIs('public.products.*') ? 'text-[#ff8400] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:bg-[#ff8400]' : '' }}">
                Catálogo Completo
            </a>
            <a href="{{ route('public.categories.index') }}"
               class="hover:text-[#ff8400] transition relative py-2 {{ request()->routeIs('public.categories.*') ? 'text-[#ff8400] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:bg-[#ff8400]' : '' }}">
                Linhas & Categorias
            </a>
            {{--
            <a href="{{ route('public.brands.index') }}"
               class="hover:text-[#ff8400] transition relative py-2 {{ request()->routeIs('public.brands.*') ? 'text-[#ff8400] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:bg-[#ff8400]' : '' }}">
                Marcas
            </a>
            --}}
        </nav>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            {{-- Search Quick Link --}}
            <a href="{{ route('public.products.index') }}"
               class="p-2.5 rounded-full text-[#544D42] hover:text-[#1C1915] hover:bg-[#E6E1D5]/60 transition"
               title="Buscar no catálogo">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
            </a>

            {{-- WhatsApp Direct CTA --}}
            <a href="{{ \App\Helpers\WhatsappLink::build(config('catalog.whatsapp.message'), ['produto' => 'Catálogo Geral', 'codigo' => 'geral']) }}"
               target="_blank" rel="noopener"
               class="hidden sm:inline-flex items-center gap-2 rounded-full bg-[#047857] text-white px-4 py-2.5 text-xs font-bold uppercase tracking-wider hover:bg-[#065F46] transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
  <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
</svg>
                <span>Falar no WhatsApp</span>
            </a>

            {{-- Mobile Drawer Toggle --}}
            <button @click="mobileOpen = !mobileOpen"
                    type="button"
                    class="md:hidden p-2.5 rounded-xl border border-[#E6E1D5] text-[#28231C] bg-white hover:bg-[#F4F1EA] transition"
                    aria-label="Abrir Menu">
                <svg x-show="!mobileOpen" class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
                <svg x-show="mobileOpen" class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Navigation Drawer --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden border-t border-[#E6E1D5] bg-[#FAFAF7] px-4 py-6 space-y-4"
         style="display: none;">
        <nav class="flex flex-col space-y-2 font-medium text-base text-[#28231C]">
            <a href="{{ route('home') }}"
               class="px-4 py-3 rounded-xl hover:bg-[#E6E1D5]/50 transition {{ request()->routeIs('home') ? 'bg-[#F4F1EA] font-bold text-[#ff8400]' : '' }}">
                Início
            </a>
            <a href="{{ route('public.products.index') }}"
               class="px-4 py-3 rounded-xl hover:bg-[#E6E1D5]/50 transition {{ request()->routeIs('public.products.*') ? 'bg-[#F4F1EA] font-bold text-[#ff8400]' : '' }}">
                Catálogo Completo
            </a>
            <a href="{{ route('public.categories.index') }}"
               class="px-4 py-3 rounded-xl hover:bg-[#E6E1D5]/50 transition {{ request()->routeIs('public.categories.*') ? 'bg-[#F4F1EA] font-bold text-[#ff8400]' : '' }}">
                Linhas & Categorias
            </a>
            {{--
            <a href="{{ route('public.brands.index') }}"
               class="px-4 py-3 rounded-xl hover:bg-[#E6E1D5]/50 transition {{ request()->routeIs('public.brands.*') ? 'bg-[#F4F1EA] font-bold text-[#ff8400]' : '' }}">
                Marcas
            </a>
            --}}
        </nav>

        <div class="pt-4 border-t border-[#E6E1D5]">
            <a href="{{ \App\Helpers\WhatsappLink::build(config('catalog.whatsapp.message'), ['produto' => 'Catálogo Geral', 'codigo' => 'geral']) }}"
               target="_blank" rel="noopener"
               class="flex items-center justify-center gap-2.5 w-full py-3.5 rounded-xl bg-[#047857] text-white font-bold text-sm hover:bg-[#065F46] transition shadow-sm">
                <svg class="size-5 fill-current" viewBox="0 0 24 24"><path d="M19.05 4.91A10 10 0 0 0 4.18 18.85L3 22l3.32-1.13A10 10 0 1 0 19.05 4.91Z"/></svg>
                Atendimento via WhatsApp
            </a>
        </div>
    </div>
</header>
