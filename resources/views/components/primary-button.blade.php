<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-[#ff8400] border border-transparent rounded-xl font-bold text-sm text-white shadow-xs hover:bg-[#e07400] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-[#ff8400]/40 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
