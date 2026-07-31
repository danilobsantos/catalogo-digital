<x-layouts.public
    :title="$category->name.' — '.config('catalog.company.name')"
    :description="$category->description ?? $category->name"
>
    <section class="container-app py-8 lg:py-14">
        <nav class="text-xs font-semibold text-[#736A5B] mb-6 flex items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-[#ff8400] transition">Início</a>
            <span class="text-[#9E9585]">/</span>
            <a href="{{ route('public.categories.index') }}" class="hover:text-[#ff8400] transition">Categorias</a>
            <span class="text-[#9E9585]">/</span>
            <span class="text-[#1C1915] font-bold">{{ $category->name }}</span>
        </nav>

        <header class="mb-8 border-b border-[#E6E1D5] pb-6">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">Linha de Produtos</p>
            <h1 class="text-3xl lg:text-4xl font-display font-bold tracking-tight text-[#1C1915] mt-1">{{ $category->name }}</h1>
            @if ($category->description)
                <p class="mt-2 text-sm text-[#544D42] max-w-2xl leading-relaxed">{{ $category->description }}</p>
            @endif
        </header>

        @if ($products->isEmpty())
            <div class="rounded-2xl bg-white border border-dashed border-[#E6E1D5] p-12 text-center">
                <p class="text-xs text-[#544D42]">Nenhum produto cadastrado nesta linha até o momento.</p>
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            <div class="mt-10">{{ $products->links('public.catalog.pagination') }}</div>
        @endif
    </section>
</x-layouts.public>

