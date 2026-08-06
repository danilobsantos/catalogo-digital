<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin · '.config('catalog.company.name'))</title>

    <link rel="icon" type="image/png" href="{{ url('favicon.png') }}">

    <!-- Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="min-h-full bg-[#FAFAF7] text-[#1C1915]">

    {{-- Topbar --}}
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-[#E6E1D5] shadow-xs">
        <div class="mx-auto flex max-w-screen-2xl items-center justify-between px-4 lg:px-8 h-16">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('logo-cj-calcados.png') }}" alt="CJ Calçados" class="size-10 object-contain transition group-hover:scale-105">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold tracking-tight text-[#1C1915]">CJ Admin</span>
                        <span class="text-[10px] uppercase tracking-wider text-[#736A5B] font-medium hidden sm:inline">Gestão de Catálogo</span>
                    </div>
                </a>
            </div>

            <nav class="hidden md:flex items-center gap-1.5 bg-[#FAFAF7] p-1 rounded-full border border-[#E6E1D5]">
                @php
                    $nav = [
                        ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
                        ['label' => 'Produtos', 'route' => 'admin.products.index'],
                        ['label' => 'Categorias', 'route' => 'admin.categories.index'],
                        ['label' => 'Coleções', 'route' => 'admin.collections.index'],
                        ['label' => 'Marcas', 'route' => 'admin.brands.index'],
                        ['label' => 'Marketing', 'route' => 'admin.marketing'],
                        ['label' => 'Banners', 'route' => 'admin.banners.index'],
                    ];
                @endphp
                @foreach ($nav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="px-4 py-1.5 text-xs font-semibold rounded-full transition
                              @if (request()->routeIs(str_replace('.index', '*', $item['route']))) bg-[#ff8400] text-white shadow-xs @else text-[#544D42] hover:text-[#1C1915] hover:bg-[#E6E1D5]/50 @endif">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" target="_blank"
                   class="hidden md:inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-[#ff8400] hover:underline">
                    <span>Ver site</span> ↗
                </a>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="size-9 rounded-full bg-[#F4F1EA] border border-[#E6E1D5] text-[#1C1915] grid place-items-center text-xs font-bold hover:bg-[#E6E1D5] transition">
                        {{ mb_substr(auth()->user()?->name ?? 'A', 0, 1) }}
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition
                         class="absolute right-0 mt-2 w-56 rounded-2xl border border-[#E6E1D5] bg-white shadow-lg overflow-hidden z-50">
                        <div class="px-4 py-3 border-b border-[#F4F1EA]">
                            <p class="text-sm font-bold text-[#1C1915] truncate">{{ auth()->user()?->name }}</p>
                            <p class="text-xs text-[#736A5B] truncate">{{ auth()->user()?->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block w-full text-left px-4 py-2.5 text-xs font-semibold text-[#544D42] hover:bg-[#FAFAF7] transition">Meu Perfil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2.5 text-xs font-semibold text-rose-600 hover:bg-[#FAFAF7] transition">Sair do Sistema</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Content --}}
    <main class="mx-auto max-w-screen-2xl px-4 lg:px-8 py-6 lg:py-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>

