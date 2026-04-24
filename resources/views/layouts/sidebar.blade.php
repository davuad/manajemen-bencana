<aside 
    class="bg-[#1E3A8A] text-white h-screen fixed left-0 top-0 w-64
    transform transition-all duration-300 ease-in-out"
    
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>

    <div class="p-4 flex items-center justify-between">
        <div x-show="sidebarOpen" x-transition class="flex items-center gap-3">
            <img src={{ asset('logo-dinsos.png') }} alt="Logo Dinsos" class="w-10 h-10 text-sm">
            <div>
                <h1 class="font-bold">Dinas Sosial</h1>
                <p class="text-xs text-gray-300">Kabupaten Cilacap</p>
            </div>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="mt-4 space-y-2 px-2">
        <a href="{{ route('admin.manajemen_user.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span><x-heroicon-o-users class="w-5 h-5" /></span>
            <span x-show="sidebarOpen" x-transition>Manajemen Pengguna</span>
        </a>

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

        <!-- ================= MANAGEMEN POSKO ================= -->
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

            <div 
                x-show="openMenu"
                x-transition
                class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2"
            >

                <a href="{{ route('management_posko.posko.index') }}"
                class="block px-3 py-2 text-sm rounded {{ request()->routeIs('management_posko.posko.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Olah Data Posko
                </a>

                <a href="{{ route('management_posko.dapur_umum.index') }}"
                class="block px-3 py-2 text-sm rounded {{ request()->routeIs('management_posko.dapur_umum.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Dapur Umum
                </a>

                <a href="{{ route('management_posko.kebutuhan_harian.index') }}"
                class="block px-3 py-2 text-sm rounded {{ request()->routeIs('management_posko.kebutuhan_harian.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Kebutuhan Harian
                </a>

            </div>
        </div>

        <!-- ================= MANAGEMEN DISTRIBUSI (BARU) ================= -->
        <div 
            x-data="{ openMenuDistribusi: {{ request()->routeIs('management_distribusi.*') ? 'true' : 'false' }} }"
            class="rounded"
        >
            <div 
                @click="openMenuDistribusi = !openMenuDistribusi"
                class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                :class="openMenuDistribusi 
                    ? 'bg-orange-500' 
                    : 'hover:bg-blue-800'"
            >
                <span>
                    <x-heroicon-o-truck class="w-5 h-5" />
                </span>
                <span x-show="sidebarOpen" x-transition>
                    Manajemen Distribusi
                </span>
            </div>

            <div 
                x-show="openMenuDistribusi"
                x-transition
                class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2"
            >

                <a href="{{ route('management_distribusi.distribusi.index') }}"
                class="block px-3 py-2 text-sm rounded {{ request()->routeIs('management_distribusi.distribusi.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Distribusi
                </a>

                

            </div>
        </div>

        <!-- ================= WARGA ================= -->
        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span>👥</span>
            <span x-show="sidebarOpen" x-transition>Warga</span>
        </a>

    </nav>

</aside>