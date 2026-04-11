<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Bencana</title>
    <link rel="icon" href="{{ asset('logo-dinsos.png') }}" type="image/png">
    @vite('resources/css/app.css')

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-100"
      x-data="{ sidebarOpen: true, loaded: false }"
      x-init="setTimeout(() => loaded = true, 50)">

<div class="flex h-screen" x-cloak>

    {{-- Sidebar --}}
    @include('layouts.sidebar')

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
            @if(isset($slot))
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