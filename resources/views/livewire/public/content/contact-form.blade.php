<div class="container-app py-12 lg:py-16">
    <header class="mb-10">
        <p class="text-xs uppercase tracking-[0.3em] text-neutral-500">Fale conosco</p>
        <h1 class="mt-2 text-4xl lg:text-5xl font-display font-semibold tracking-tight">Contato</h1>
        <p class="mt-3 max-w-xl text-neutral-600 dark:text-neutral-300">
            Tem dúvidas sobre um modelo, quer um orçamento ou representa nossa marca? Preencha o formulário.
            Respondemos em até <strong>1 dia útil</strong>.
        </p>
    </header>

    @if (session('flash.success'))
        <div class="mb-8 rounded-xl border border-emerald-200 bg-emerald-50 dark:border-emerald-800/50 dark:bg-emerald-900/20 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-300">
            {{ session('flash.success') }}
        </div>
    @endif

    <div class="grid gap-10 lg:grid-cols-[1fr_360px]">
        <form wire:submit="submit" class="grid gap-5 max-w-2xl">
            <flux:fieldset class="grid gap-5">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs uppercase tracking-wide text-neutral-500">Nome *</label>
                        <input type="text" wire:model="name" maxlength="120" required
                               class="mt-1 w-full rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-0 dark:bg-neutral-950 px-3 py-2 text-sm">
                        @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs uppercase tracking-wide text-neutral-500">E-mail *</label>
                        <input type="email" wire:model="email" maxlength="180" required
                               class="mt-1 w-full rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-0 dark:bg-neutral-950 px-3 py-2 text-sm">
                        @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs uppercase tracking-wide text-neutral-500">WhatsApp</label>
                        <input type="tel" wire:model="phone" maxlength="32"
                               class="mt-1 w-full rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-0 dark:bg-neutral-950 px-3 py-2 text-sm">
                        @error('phone') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs uppercase tracking-wide text-neutral-500">Assunto *</label>
                        <select wire:model="subject"
                                class="mt-1 w-full rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-0 dark:bg-neutral-950 px-3 py-2 text-sm">
                            <option>Catálogo</option>
                            <option>Orçamento</option>
                            <option>Parceria</option>
                            <option>Outros</option>
                        </select>
                        @error('subject') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="text-xs uppercase tracking-wide text-neutral-500">Mensagem *</label>
                    <textarea wire:model="message" rows="6" minlength="30" required
                              class="mt-1 w-full rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-0 dark:bg-neutral-950 px-3 py-2 text-sm"></textarea>
                    @error('message') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <button type="submit" wire:loading.attr="disabled"
                        class="rounded-full bg-neutral-900 dark:bg-neutral-100 text-neutral-0 dark:text-neutral-900 px-5 py-3 text-sm font-semibold hover:opacity-90 self-start">
                    <span wire:loading.remove>Enviar mensagem</span>
                    <span wire:loading>Enviando…</span>
                </button>
            </flux:fieldset>
        </form>

        <aside class="space-y-8 text-sm">
            <section>
                <h2 class="text-xs uppercase tracking-[0.3em] text-neutral-500">Direto</h2>
                <ul class="mt-3 space-y-2">
                    <li>
                        <a href="{{ \App\Helpers\WhatsappLink::build(config('catalog.whatsapp.message'), ['produto' => 'contato', 'codigo' => 'site']) }}" target="_blank" rel="noopener"
                          class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-4 py-2 text-white font-semibold">
                            <svg class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M19.05 4.91A10 10 0 1 0 4.18 18.85L3 22l3.32-1.13A10 10 0 0 0 19.05 4.91Z"/></svg>
                            WhatsApp
                        </a>
                    </li>
                    <li>E-mail: <a href="mailto:{{ config('catalog.contact.recipient', 'admin@cjcalcados.com.br') }}" class="hover:underline">{{ config('catalog.contact.recipient', 'admin@cjcalcados.com.br') }}</a></li>
                    <li>Telefone: <a href="tel:+{{ config('catalog.whatsapp.number') }}" class="hover:underline">+{{ config('catalog.whatsapp.number') }}</a></li>
                </ul>
            </section>

            <section>
                <h2 class="text-xs uppercase tracking-[0.3em] text-neutral-500">Endereço</h2>
                <p class="mt-3 text-neutral-600 dark:text-neutral-300">
                    {{ config('catalog.company.name') }}<br>
                    Atendemos o Brasil inteiro via catálogo digital.
                </p>
            </section>

            <section>
                <h2 class="text-xs uppercase tracking-[0.3em] text-neutral-500">Horário</h2>
                <p class="mt-3 text-neutral-600 dark:text-neutral-300">
                    Seg à Sex — 9h às 18h.<br>
                    Sáb — 9h às 13h.
                </p>
            </section>
        </aside>
    </div>
</div>
