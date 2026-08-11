<footer class="mt-20 border-t border-[#E6E1D5] bg-[#F4F1EA] text-[#3D372E]">
    <div class="container-app py-14 lg:py-16">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Brand info --}}
            <div class="space-y-4">
                <div class="flex items-center justify-center sm:justify-start gap-3">
                    <img src="{{ asset('logo-full.png') }}" alt="CJ Calçados" width="180" height="180" class="w-36 sm:w-44 h-auto object-contain">
                </div>
                <div class="pt-1 flex items-center justify-center sm:justify-start gap-2.5">
                    @if(config('catalog.social.instagram') !== '')
                        <a href="{{ config('catalog.social.instagram') }}" target="_blank" rel="noopener" aria-label="Instagram" class="inline-flex items-center justify-center size-9 rounded-full bg-[#EAE5D9] text-[#544D42] hover:bg-[#ff8400] hover:text-white transition-colors duration-200" title="Instagram">
                            <svg class="size-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    @endif

                    @if(config('catalog.social.facebook') !== '')
                        <a href="{{ config('catalog.social.facebook') }}" target="_blank" rel="noopener" aria-label="Facebook" class="inline-flex items-center justify-center size-9 rounded-full bg-[#EAE5D9] text-[#544D42] hover:bg-[#ff8400] hover:text-white transition-colors duration-200" title="Facebook">
                            <svg class="size-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    @endif

                    @if(config('catalog.social.tiktok') !== '')
                        <a href="{{ config('catalog.social.tiktok') }}" target="_blank" rel="noopener" aria-label="TikTok" class="inline-flex items-center justify-center size-9 rounded-full bg-[#EAE5D9] text-[#544D42] hover:bg-[#ff8400] hover:text-white transition-colors duration-200" title="TikTok">
                            <svg class="size-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                            </svg>
                        </a>
                    @endif
                </div>
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
            <p>© {{ date('Y') }} {{ config('catalog.company.name') }} | Desenvolvido por <a href="https://devstudio.com.br" target="_blank" rel="noopener" class="font-medium text-[#ff8400]">DevStudio - Inovação Digital</a></p>
            <p class="font-medium text-[#544D42]">Catálogo Digital de Calçados</p>
        </div>
    </div>
</footer>
