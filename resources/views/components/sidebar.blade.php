<aside 
    class="bg-[#1E3A8A] text-white h-screen fixed left-0 top-0 w-64
    transform transition-all duration-300 ease-in-out"
    
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>

    <div class="p-4 flex items-center justify-between">
        <div x-show="sidebarOpen" x-transition class="flex items-center gap-3">
            <img src="logo-dinsos.png" alt="Logo Dinsos" class="w-13 h-13">
            <div>
                <h1 class="font-bold">Dinas Sosial</h1>
                <p class="text-xs text-gray-300">Kabupaten Cilacap</p>
            </div>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="mt-4 space-y-2 px-2">

        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span><x-heroicon-o-exclamation-triangle class="w-5 h-5" /></span>
            <span x-show="sidebarOpen" x-transition>Pengaduan Bencana</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span><x-heroicon-o-squares-2x2 class="w-5 h-5" /></span>
            <span x-show="sidebarOpen" x-transition>Kategori Bencana</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span><x-heroicon-o-archive-box class="w-5 h-5" /></span>
            <span x-show="sidebarOpen" x-transition>Gudang Logistik</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span><x-heroicon-o-truck class="w-5 h-5" /></span>
            <span x-show="sidebarOpen" x-transition>Distribusi Bantuan</span>
        </a>

            <div 
                x-data="{ openMenu: {{ request()->routeIs('management_posko.*') ? 'true' : 'false' }} }"
                class="rounded"
            >
                <div 
                    @click="openMenu = !openMenu"
                    class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                    :class="openMenu 
                        ? 'bg-orange-500' 
                        : 'hover:bg-blue-800'"
                >
                    <span>
                        <x-heroicon-o-home-modern class="w-5 h-5" />
                    </span>
                    <span x-show="sidebarOpen" x-transition>
                        Manajemen Posko
                    </span>
                </div>

                <!-- Dropdown Menu -->
                <div 
                    x-show="openMenu"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden"
                >

                    <!-- Olah Data Posko -->
                    <a href="{{ route('management_posko.posko.index') }}"
                    class="block px-3 py-2 text-sm rounded transition-all duration-200"
                    :class="{
                        'bg-white/10': {{ request()->routeIs('management_posko.posko.index') ? 'true' : 'false' }},
                        'hover:bg-blue-700': !{{ request()->routeIs('management_posko.posko.index') ? 'true' : 'false' }}
                    }"
                    >
                        Olah Data Posko
                    </a>

                    <!-- Dapur Umum -->
                    <a href="#"
                    class="block px-3 py-2 text-sm rounded transition-all duration-200"
                    :class="{
                        'bg-white/10': {{ request()->routeIs('management_posko.dapur.*') ? 'true' : 'false' }},
                        'hover:bg-blue-700': !{{ request()->routeIs('management_posko.dapur.*') ? 'true' : 'false' }}
                    }"
                    >
                        Dapur Umum
                    </a>

                    <!-- Kebutuhan Harian -->
                    <a href="#"
                    class="block px-3 py-2 text-sm rounded transition-all duration-200"
                    :class="{
                        'bg-blue-900': {{ request()->routeIs('management_posko.kebutuhan.*') ? 'true' : 'false' }},
                        'hover:bg-blue-700': !{{ request()->routeIs('management_posko.kebutuhan.*') ? 'true' : 'false' }}
                    }"
                    >
                        Kebutuhan Harian
                    </a>

                </div>

            </div>

        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span>👥</span>
            <span x-show="sidebarOpen" x-transition>Warga</span>
        </a>

    </nav>

</aside>