@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 text-sm border-[#E6E1D5] bg-white text-[#1C1915] placeholder-[#9E9585] focus:border-[#ff8400] focus:ring-2 focus:ring-[#ff8400]/20 rounded-xl shadow-xs transition duration-150 ease-in-out']) }}>
