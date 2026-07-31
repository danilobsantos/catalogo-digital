<footer class="mt-20 border-t border-[#E6E1D5] bg-[#F4F1EA] text-[#3D372E]">
    <div class="container-app py-14 lg:py-16">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Brand info --}}
            <div class="space-y-4">
                <div class="flex items-center justify-center sm:justify-start gap-3">
                    <img src="{{ asset('logo-full.png') }}" alt="CJ Calçados" width="180" height="180" class="w-36 sm:w-44 h-auto object-contain">
                </div>
                <!-- <p class="text-sm text-[#544D42] leading-relaxed">
                    Especialista em botinas e calçados de couro premium. Qualidade, durabilidade e conforto em cada detalhe.
                </p> -->
            </div>

            {{-- Quick links --}}
            <div>
                <h4 class="font-display font-bold text-xs uppercase tracking-wider text-[#1C1915] mb-4">Navegação</h4>
                <ul class="space-y-2.5 text-sm font-medium text-[#544D42]">
                    <li><a href="{{ route('home') }}" class="hover:text-[#ff8400] transition">Início</a></li>
                    <li><a href="{{ route('public.products.index') }}" class="hover:text-[#ff8400] transition">Catálogo de Produtos</a></li>
                    <li><a href="{{ route('public.categories.index') }}" class="hover:text-[#ff8400] transition">Linhas & Categorias</a></li>
                    {{-- <li><a href="{{ route('public.brands.index') }}" class="hover:text-[#ff8400] transition">Marcas Parceiras</a></li> --}}
                </ul>
            </div>

            {{-- Contact / Support --}}
            <div>
                <h4 class="font-display font-bold text-xs uppercase tracking-wider text-[#1C1915] mb-4">Atendimento</h4>
                <ul class="space-y-2.5 text-sm text-[#544D42]">
                    <li class="flex items-center gap-2">
                        <span class="size-2 rounded-full bg-[#047857]"></span>
                        <span>Vendas e Orçamentos Online</span>
                    </li>
                    <li>
                        <a href="{{ \App\Helpers\WhatsappLink::build(config('catalog.whatsapp.message'), ['produto' => 'Atendimento', 'codigo' => 'geral']) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 text-sm font-semibold text-[#047857] hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
  <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
</svg>
                            WhatsApp
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Quality Seal --}}
            <div class="rounded-2xl bg-[#FAFAF7] border border-[#E6E1D5] p-5 space-y-3">
                <span class="inline-block px-2.5 py-1 rounded-md bg-[#FAF6F0] text-[#ff8400] text-[11px] font-bold uppercase tracking-wider">
                    Garantia de Qualidade
                </span>
                <h5 class="font-display font-bold text-sm text-[#1C1915]">Couro Legítimo & C.A.</h5>
                <p class="text-xs text-[#736A5B] leading-relaxed">
                    Modelos selecionados com Certificado de Aprovação (C.A.) e mataria prima testada.
                </p>
            </div>
        </div>

        <div class="mt-12 pt-6 border-t border-[#E6E1D5] flex flex-col sm:flex-row items-center justify-between text-xs text-[#736A5B] gap-4">
            <p>© {{ date('Y') }} {{ config('catalog.company.name') }}. Todos os direitos reservados.</p>
            <p class="font-medium text-[#544D42]">Catálogo Digital de Calçados</p>
        </div>
    </div>
</footer>
