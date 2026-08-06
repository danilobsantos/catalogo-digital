<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-[#DC2626] border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider shadow-xs hover:bg-[#B91C1C] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-[#DC2626]/40 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
