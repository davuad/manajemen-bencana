<header class="bg-white sticky top-0 shadow px-6 py-4 flex justify-between items-center">

    <div class="flex items-center gap-4">

        {{-- Toggle --}}
        <button @click="sidebarOpen = !sidebarOpen" class="text-xl hover:bg-gray-100">
            <x-heroicon-o-bars-3 class="w-6 h-6" />
        </button>

        <h1 class="font-semibold text-gray-700">
            Sistem Manajemen Bencana
        </h1>

    </div>

    <div class="flex items-center gap-4">
        <span><x-heroicon-o-bell class="w-5 h-5" /></span>
        <span><x-heroicon-o-cog-6-tooth class="w-5 h-5" /></span>

        <div class="flex items-center gap-2">
            <div>
                <p class="text-sm font-semibold">Admin</p>
                <p class="text-xs text-gray-500">Super Admin</p>
            </div>
            <img src="https://i.pravatar.cc/40" class="rounded-full">
        </div>

    </div>

</header>