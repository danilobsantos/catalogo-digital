<x-layouts.public title="Marcas — {{ config('catalog.company.name') }}">
    <section class="container-app py-8 lg:py-14">
        <header class="mb-8 border-b border-[#E6E1D5] pb-6">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">Fabricantes & Parceiros</p>
            <h1 class="text-3xl lg:text-4xl font-display font-bold tracking-tight text-[#1C1915] mt-1">Marcas Parceiras</h1>
            <p class="mt-2 text-sm text-[#544D42] max-w-xl">Trabalhamos com as melhores marcas de calçados de couro e botinas do mercado.</p>
        </header>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($brands as $brand)
                <a href="{{ route('public.brands.show', $brand->slug) }}"
                   class="group relative overflow-hidden rounded-2xl bg-white border border-[#E6E1D5] p-6 transition-all duration-300 hover:border-[#ff8400]/60 hover:shadow-md">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-[#736A5B]">Marca Parceira</span>
                    <h3 class="mt-2 text-xl font-display font-bold text-[#1C1915] group-hover:text-[#ff8400] transition">{{ $brand->name }}</h3>
                    <p class="mt-2 text-xs text-[#544D42] leading-relaxed line-clamp-3">{{ $brand->description }}</p>
                    <div class="mt-6 pt-4 border-t border-[#F4F1EA] flex items-center justify-between text-xs font-bold text-[#ff8400]">
                        <span>Ver modelos desta marca</span>
                        <span class="group-hover:translate-x-1 transition">→</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
</x-layouts.public>

