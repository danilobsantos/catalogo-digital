<x-layouts.public :title="'Painel · '.config('catalog.company.name')">
    <main class="min-h-screen px-6 py-16 bg-[#FAFAF7]">
        <div class="max-w-3xl mx-auto bg-white border border-[#E6E1D5] rounded-3xl p-8 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#ff8400]">Painel de Controle</p>
            <h1 class="mt-2 text-3xl font-display font-bold text-[#1C1915]">
                Bem-vindo, {{ auth()->user()?->name }}.
            </h1>
            <p class="mt-2 text-[#544D42] text-sm">
                Empresa ativa: <strong class="text-[#1C1915] font-bold">{{ auth()->user()?->activeCompany?->name }}</strong>
            </p>
            <p class="mt-1 text-xs text-[#736A5B]">
                Papéis: {{ auth()->user()?->getRoleNames()->implode(', ') }}
            </p>

            <div class="mt-8 pt-6 border-t border-[#F4F1EA] flex items-center gap-4">
                <a href="{{ route('admin.products.index') }}" class="rounded-full bg-[#ff8400] text-white px-5 py-2.5 text-xs font-bold uppercase tracking-wider hover:bg-[#A84D29] transition shadow-xs">
                    Ir para Administração de Produtos
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-full border border-[#E6E1D5] bg-[#FAFAF7] px-5 py-2.5 text-xs font-bold text-[#544D42] hover:bg-[#E6E1D5] transition">
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </main>
</x-layouts.public>

