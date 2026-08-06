<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Bencana</title>
    <link rel="icon" href="{{ asset('logo-dinsos.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite('resources/css/app.css')

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100" x-data="{ sidebarOpen: true, loaded: false }" x-init="setTimeout(() => loaded = true, 50)">

    <div class="flex h-screen" x-cloak>

        {{-- Sidebar --}}

        @include('layouts.sidebar')

        {{-- Kalau mau gunakan sidebar yang aku buat pakai ini, kalau gk mau gk usah di nyalain  --}}

        {{-- @if (auth()->user()->hasRole('admin'))
            @include('layouts.sidebar')
        @elseif(auth()->user()->hasRole('kabid'))
            @include('layouts.sidebar.sidebar_kabid')
        @elseif(auth()->user()->hasRole('ketua_tim'))
            @include('layouts.sidebar.sidebar_ketua_tim')
        @elseif(auth()->user()->hasRole('relawan'))
            @include('layouts.sidebar.sidebar_relawan')
        @elseif(auth()->user()->hasRole('desa'))
            @include('layouts.sidebar.sidebar_desa')
        @elseif(auth()->user()->hasRole('kadus'))
            @include('layouts.sidebar.sidebar_kadus')
        @endif --}}

        {{-- Content --}}
        <div class="flex-1 flex flex-col"
            :class="[
                sidebarOpen ? 'ml-64' : 'ml-0',
                loaded ? 'transition-all duration-300' : ''
            ]">

            @include('layouts.navigation')
            {{-- @include('components.navbar') --}}

            {{-- Page Heading --}}
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Main Content --}}

            <main class="flex-1 overflow-auto p-6">
                @if (session('success'))
                    <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 border border-green-400 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 border border-red-400 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif
                @if (isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>

    </div>

    {{-- Alpine --}}
    <script src="//unpkg.com/alpinejs" defer></script>

</body>

</html>
