@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-neutral-700']) }}>
    {{ $value ?? $slot }}
</label>
