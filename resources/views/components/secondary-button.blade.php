<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-[#F4F1EA] border border-[#E6E1D5] rounded-xl font-semibold text-sm text-[#3D372E] shadow-xs hover:bg-[#E6E1D5] hover:text-[#1C1915] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-[#E6E1D5] disabled:opacity-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
