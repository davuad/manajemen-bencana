<aside
    class="bg-[#1E3A8A] text-white h-screen fixed left-0 top-0 w-64 overflow-y-auto"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

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
        @role('admin')
            <a href="{{ route('admin.manajemen_user.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
                <span><x-heroicon-o-users class="w-5 h-5" /></span>
                <span x-show="sidebarOpen" x-transition>Manajemen Pengguna</span>
            </a>
        @endrole

        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span><x-heroicon-o-exclamation-triangle class="w-5 h-5" /></span>
            <span x-show="sidebarOpen" x-transition>Pengaduan Bencana</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span><x-heroicon-o-archive-box class="w-5 h-5" /></span>
            <span x-show="sidebarOpen" x-transition>Gudang Logistik</span>
        </a>

        {{-- <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span><x-heroicon-o-truck class="w-5 h-5" /></span>
            <span x-show="sidebarOpen" x-transition>Distribusi Bantuan</span>
        </a> --}}

        <!-- ================= MANAGEMEN POSKO ================= -->
        
        <!-- KATEGORI BENCANA -->
        <a href="{{ route('kategori_bencana.index') }}" 
        class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
        <span><x-heroicon-o-squares-2x2 class="w-5 h-5" /></span>
        <span x-show="sidebarOpen" x-transition>Kategori Bencana</span>
        </a>

        <!-- DATA BENCANA -->
        <a href="{{ route('bencana.index') }}" 
        class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
        {{ request()->routeIs('bencana.*') ? 'bg-blue-700' : 'hover:bg-blue-800' }}">
            <span>
                <x-heroicon-o-fire class="w-5 h-5" />
            </span>

            <span x-show="sidebarOpen" x-transition>
                Data Bencana
            </span>
        </a>

        <!-- KATEGORI BANTUAN -->
        <a href="{{ route('kategori_bantuan.index') }}" 
        class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
        {{ request()->routeIs('kategori_bantuan.*') ? 'bg-blue-700' : 'hover:bg-blue-800' }}">

            <span>
                <x-heroicon-o-cube class="w-5 h-5" />
            </span>

            <span x-show="sidebarOpen" x-transition>
                Kategori Bantuan
            </span>
        </a>

        <!-- DATA GUDANG -->
        <a href="{{ route('gudang.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded 
            {{ request()->routeIs('gudang.*') ? 'bg-blue-700' : 'hover:bg-blue-800' }}">

                <x-heroicon-o-building-storefront class="w-5 h-5" />

                <span x-show="sidebarOpen">
                    Data Gudang
                </span>
            </a>

            <!-- STOK GUDANG -->
                <a href="{{ route('stok_gudang.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded 
                    {{ request()->routeIs('stok.*') ? 'bg-blue-700' : 'hover:bg-blue-800' }}">

                    <span>
                        <x-heroicon-o-archive-box-arrow-down class="w-5 h-5" />
                    </span>

                    <span x-show="sidebarOpen">
                        Stok Gudang
                    </span>
                </a>

                  <div 
    x-data="{ openMenu: {{ request()->routeIs('desa.*') || request()->routeIs('warga.*') ? 'true' : 'false' }} }"
    class="rounded"
>
    <div 
        @click="openMenu = !openMenu"
        class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
        :class="openMenu ? 'bg-orange-500' : 'hover:bg-blue-800'"
    >
        <span>
            <x-heroicon-o-folder class="w-5 h-5" />
        </span>
        <span x-show="sidebarOpen" x-transition>
            Data Wilayah & Warga
        </span>
    </div>

    <div 
        x-show="openMenu"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2"
    >
        <a href="{{ route('desa.index') }}"
           class="block px-3 py-2 text-sm rounded transition-all duration-200 {{ request()->routeIs('desa.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
            Data Desa
        </a>

        <a href="{{ route('warga.index') }}"
           class="block px-3 py-2 text-sm rounded transition-all duration-200 {{ request()->routeIs('warga.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
            Data Warga Terdampak
        </a>
    </div>
</div>
        <div 
            x-data="{ openMenu: {{ request()->routeIs('management_posko.*') ? 'true' : 'false' }} }"
            class="rounded"
        >
            <div 
                @click="openMenu = !openMenu"
                class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                :class="openMenu
                    ?
                    'bg-orange-500' :
                    'hover:bg-blue-800'">
                <span>
                    <x-heroicon-o-home-modern class="w-5 h-5" />
                </span>
                <span x-show="sidebarOpen" x-transition>
                    Manajemen Posko
                </span>
            </div>

            <div x-show="openMenu" x-transition class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">

                <a href="{{ route('management_posko.posko.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('management_posko.posko.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Olah Data Posko
                </a>

                <a href="{{ route('management_posko.dapur_umum.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('management_posko.dapur_umum.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Dapur Umum
                </a>

                <a href="{{ route('management_posko.dapur_umum.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('management_posko.kebutuhan_harian.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Kebutuhan Harian
                </a>

            </div>
        </div>

        <!-- ================= MANAGEMEN DISTRIBUSI (BARU) ================= -->
        <div x-data="{ openMenuDistribusi: {{ request()->routeIs('management_distribusi.*') ? 'true' : 'false' }} }" class="rounded">
            <div @click="openMenuDistribusi = !openMenuDistribusi"
                class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                :class="openMenuDistribusi
                    ?
                    'bg-orange-500' :
                    'hover:bg-blue-800'">
                <span>
                    <x-heroicon-o-truck class="w-5 h-5" />
                </span>
                <span x-show="sidebarOpen" x-transition>
                    Manajemen Distribusi
                </span>
            </div>

            <div x-show="openMenuDistribusi" x-transition class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">

                <a href="{{ route('management_distribusi.distribusi.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('management_distribusi.distribusi.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Distribusi
                </a>
                <a href="{{ route('management_distribusi.paket_bantuan.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('management_distribusi.paket_bantuan.*') || request()->routeIs('management_distribusi.detail_paket.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Paket Bantuan
                </a>
                <a href="{{ route('management_distribusi.distribusi_paket.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('management_distribusi.distribusi_paket.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Distribusi Pasca Bencana
                </a>


            </div>
        </div>

        <!-- ================= DATA KORBAN ================= -->
        <a href="{{ route('management_korban.korban.index') }}"
        class="flex items-center gap-3 px-3 py-2 rounded 
        {{ request()->routeIs('management_korban.korban.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">
            
            <span>
                <x-heroicon-o-user-group class="w-5 h-5" />
            </span>

            <span x-show="sidebarOpen" x-transition>
                Data Korban
            </span>
        </a>

        <!-- ================= WARGA ================= -->
        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span>👥</span>
            <span x-show="sidebarOpen" x-transition>Warga</span>
        </a>

<!-- ================= MANAJEMEN PEGAWAI ================= -->
<div
    x-data="{ openMenuPegawai: {{ request()->routeIs('management_pegawai.*') ? 'true' : 'false' }} }"
    class="rounded">

    <div
        @click="openMenuPegawai = !openMenuPegawai"
        class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
        :class="openMenuPegawai ? 'bg-orange-500' : 'hover:bg-blue-800'">

        <span>
            <x-heroicon-o-users class="w-5 h-5" />
        </span>

        <span x-show="sidebarOpen" x-transition>
            Manajemen Pegawai
        </span>
    </div>

    <div
        x-show="openMenuPegawai"
        x-transition
        class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">

        <a href="{{ route('management_pegawai.pegawai.index') }}"
            class="block px-3 py-2 text-sm rounded {{ request()->routeIs('management_pegawai.pegawai.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
            Data Pegawai
        </a>

        <a href="{{ route('management_pegawai.relawan.index') }}"
            class="block px-3 py-2 text-sm rounded {{ request()->routeIs('management_pegawai.relawan.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
            Data Relawan
        </a>

    </div>
</div>

    </nav>

</aside>
