@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navegação de Páginas" class="flex items-center justify-between">
        {{-- Mobile view --}}
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-[#736A5B] bg-[#F4F1EA] border border-[#E6E1D5] cursor-default rounded-xl uppercase tracking-wider">
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-[#1C1915] bg-white border border-[#E6E1D5] hover:bg-[#F4F1EA] rounded-xl transition shadow-xs uppercase tracking-wider">
                    Anterior
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-[#1C1915] bg-white border border-[#E6E1D5] hover:bg-[#F4F1EA] rounded-xl transition shadow-xs uppercase tracking-wider">
                    Próximo
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-[#736A5B] bg-[#F4F1EA] border border-[#E6E1D5] cursor-default rounded-xl uppercase tracking-wider">
                    Próximo
                </span>
            @endif
        </div>

        {{-- Desktop view --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-xs sm:text-sm text-[#544D42]">
                    Mostrando
                    <span class="font-bold text-[#1C1915]">{{ $paginator->firstItem() }}</span>
                    a
                    <span class="font-bold text-[#1C1915]">{{ $paginator->lastItem() }}</span>
                    de
                    <span class="font-bold text-[#1C1915]">{{ $paginator->total() }}</span>
                    resultados
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-xs rounded-xl overflow-hidden border border-[#E6E1D5] bg-white">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Anterior">
                            <span class="relative inline-flex items-center px-3 py-2.5 text-sm font-medium text-[#9E9585] bg-[#FAFAF7] cursor-default" aria-hidden="true">
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2.5 text-sm font-medium text-[#544D42] hover:bg-[#F4F1EA] transition" aria-label="Anterior">
                            <svg class="size-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2.5 text-sm font-medium text-[#736A5B] bg-[#FAFAF7] cursor-default">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2.5 text-sm font-bold text-white bg-[#ff8400] cursor-default">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2.5 text-sm font-medium text-[#544D42] hover:bg-[#F4F1EA] transition" aria-label="Ir para a página {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2.5 text-sm font-medium text-[#544D42] hover:bg-[#F4F1EA] transition" aria-label="Próximo">
                            <svg class="size-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Próximo">
                            <span class="relative inline-flex items-center px-3 py-2.5 text-sm font-medium text-[#9E9585] bg-[#FAFAF7] cursor-default" aria-hidden="true">
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
