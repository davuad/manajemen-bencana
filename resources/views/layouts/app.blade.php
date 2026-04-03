<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Bencana</title>
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
    @include('components.sidebar')

    {{-- Content --}}
    <div class="flex-1 flex flex-col"
         :class="[
            sidebarOpen ? 'ml-64' : 'ml-0',
            loaded ? 'transition-all duration-300' : ''
         ]">

        @include('components.navbar')

        <main class="p-6">
            @yield('content')
        </main>
    </div>

</div>

{{-- Alpine --}}
<script src="//unpkg.com/alpinejs" defer></script>

</body>
</html>