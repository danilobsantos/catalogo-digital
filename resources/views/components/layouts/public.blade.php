<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0a">
    <meta name="language" content="Portuguese">
    @hasSection('head_extra_lang')
        @yield('head_extra_lang')
    @endif

    <title>{{ $title ?? config('catalog.seo.title_default') }}</title>
    <meta name="description" content="{{ $description ?? config('catalog.seo.description_default') }}">
    <meta name="robots" content="index,follow,max-image-preview:large">

    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="pt-BR" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ ($title ?? config('catalog.seo.title_default')) }}">
    <meta property="og:description" content="{{ $description ?? config('catalog.seo.description_default') }}">
    <meta property="og:type" content="{{ $type ?? 'website' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ config('catalog.company.name') }}">
    <meta property="og:locale" content="pt_BR">
    @if (! empty($image))
        <meta property="og:image" content="{{ $image }}">
        <meta name="twitter:image" content="{{ $image }}">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? config('catalog.seo.title_default') }}">
    <meta name="twitter:description" content="{{ $description ?? config('catalog.seo.description_default') }}">

    <link rel="icon" type="image/png" href="/favicon.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{ $head ?? '' }}

    @livewireStyles
</head>

<body class="min-h-screen flex flex-col bg-[#FAFAF7] text-[#1C1915] dark:bg-neutral-950 dark:text-neutral-100 antialiased">

    {{-- Header Público --}}
    <x-header-public />

    {{-- Conteúdo Principal --}}
    <main class="flex-grow">
        {{ $slot ?? '' }}
    </main>

    {{-- Footer Público --}}
    <x-footer-public />

    @livewireScripts
</body>

</html>


