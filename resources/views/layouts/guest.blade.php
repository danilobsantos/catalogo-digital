<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/png" href="{{ url('favicon.png') }}">

        <!-- Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-neutral-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-10 bg-neutral-50">
            <div class="w-full sm:max-w-md">
                <a href="/" class="flex flex-col items-center gap-3 mb-8 group">
                    <img src="{{ asset('logo-cj-calcados.png') }}" alt="CJ Calçados" class="size-16 object-contain transition group-hover:scale-105">
                    <span class="text-[10px] uppercase tracking-[0.15em] text-neutral-500 font-medium">Indústria de Calçados</span>
                </a>

                <div class="bg-white border border-neutral-200 rounded-2xl shadow-warm-lg overflow-hidden">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
