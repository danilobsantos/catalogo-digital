<x-layouts.public
    title="Linhas & Categorias — {{ config('catalog.company.name') }}"
    description="Conheça todas as linhas de calçados: Botinas de Segurança, Coturnos, Texanas e Linha Casual."
>
    <section class="container-app py-8 lg:py-14">
        <header class="mb-8 border-b border-[#E6E1D5] pb-6">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">Curadoria Especial</p>
            <h1 class="text-3xl lg:text-4xl font-display font-bold tracking-tight text-[#1C1915] mt-1">Linhas de Produtos</h1>
            <p class="mt-2 text-sm text-[#544D42] max-w-xl">Navegue pelas nossas linhas exclusivas de calçados de couro de alta durabilidade e conforto.</p>
        </header>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($categories as $category)
                <a href="{{ route('public.categories.show', $category->slug) }}"
                   class="group relative overflow-hidden rounded-2xl bg-white border border-[#E6E1D5] p-6 transition-all duration-300 hover:border-[#ff8400]/60 hover:shadow-md">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-[#736A5B]">Categoria</span>
                    <h3 class="mt-2 text-xl font-display font-bold text-[#1C1915] group-hover:text-[#ff8400] transition">{{ $category->name }}</h3>
                    <p class="mt-2 text-xs text-[#544D42] leading-relaxed line-clamp-3">{{ $category->description }}</p>
                    <div class="mt-6 pt-4 border-t border-[#F4F1EA] flex items-center justify-between text-xs font-bold text-[#ff8400]">
                        <span>Ver modelos desta linha</span>
                        <span class="group-hover:translate-x-1 transition">→</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
</x-layouts.public>

