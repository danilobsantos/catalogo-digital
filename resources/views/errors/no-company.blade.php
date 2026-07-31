<x-layouts.public>
    <main class="min-h-screen flex items-center justify-center px-6 py-24">
        <div class="max-w-md text-center">
            <p class="text-xs uppercase tracking-[0.3em] text-danger-500">Erro 422</p>
            <h1 class="mt-4 text-3xl font-display font-semibold text-neutral-900 dark:text-neutral-0">
                Empresa não selecionada
            </h1>
            <p class="mt-4 text-neutral-600 dark:text-neutral-300">{{ $message }}</p>
            <a href="{{ route('home') }}"
               class="mt-8 inline-block rounded-full bg-neutral-900 px-6 py-3 text-sm font-medium text-neutral-0 hover:bg-neutral-700">
                Voltar ao início
            </a>
        </div>
    </main>
</x-layouts.public>
