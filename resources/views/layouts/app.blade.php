<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard E-Ticketing')</title>

    {{-- Font & Icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- CSS Utama (Vite & Custom CSS) --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-light" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">

    <div class="wrapper">
        {{-- Sidebar (Menggunakan class dari dashboard.css) --}}
        {{-- Logika: 'show-mobile' aktif jika mobileMenuOpen true --}}
        <div class="sidebar-blue" :class="{ 'show-mobile': mobileMenuOpen }">
            @auth
                @if(auth()->user()->role === 'pengelola')
                    @include('layouts.sidebar.pengelola')
                @else
                    @include('layouts.sidebar.petugas_loket')
                @endif
            @endauth
        </div>

        {{-- Konten Kanan --}}
        {{-- Logika: 'ml-sidebar' aktif jika sidebarOpen true --}}
        <div class="content-area" :class="sidebarOpen ? 'ml-sidebar' : 'ml-zero'">
            
            @include('layouts.navigation')

            <main class="p-4">
                @yield('content')
            </main>
        </div>

        {{-- Backdrop untuk HP (muncul saat menu mobile dibuka) --}}
        <div class="sidebar-backdrop" 
             x-show="mobileMenuOpen" 
             @click="mobileMenuOpen = false" 
             x-cloak>
        </div>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>