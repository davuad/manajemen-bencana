<aside class="bg-[#1E3A8A] text-white h-screen fixed left-0 top-0 w-64 overflow-y-auto"
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
    <nav class="mt-4 space-y-2 px-2  pb-12">
        @role('admin')
            <a href=" {{ route('admin.management_user.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
                <span><x-heroicon-o-users class="w-5 h-5" /></span>
                <span x-show="sidebarOpen" x-transition>Manajemen User</span>
            </a>
        @endrole

        {{-- PENGADUAN BENCANA --}}

        @if (auth()->user()->hasRole('admin'))
            <a href="{{ route('admin.pengaduan_bencana.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">

                <span>
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
                </span>

                <span x-show="sidebarOpen">
                    Pengaduan Bencana
                </span>

            </a>
        @elseif(auth()->user()->hasRole('kabid'))
            <a href="{{ route('kabid.pengaduan.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">

                <span>
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
                </span>

                <span x-show="sidebarOpen">
                    Verifikasi Pengaduan
                </span>

            </a>
        @elseif(auth()->user()->hasRole('ketua_tim'))
            <a href="{{ route('ketua_tim.pengaduan.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">

                <span>
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
                </span>

                <span x-show="sidebarOpen">
                    Monitoring Pengaduan
                </span>

            </a>
        @elseif(auth()->user()->hasRole('relawan') || auth()->user()->hasRole('kadus') || auth()->user()->hasRole('desa'))
            <a href="{{ route('user.pengaduan.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">

                <span>
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
                </span>

                <span x-show="sidebarOpen">
                    Pengaduan Saya
                </span>

            </a>
        @endif

        <div x-data="{ openGudang: {{ request()->routeIs('admin.jenis-barang.*') ||
        request()->routeIs('admin.sumber-barang.*') ||
        request()->routeIs('admin.barang.*') ||
        request()->routeIs('admin.barang-masuk.*')
            ? 'true'
            : 'false' }} }" class="rounded">

            <div @click="openGudang = !openGudang"
                class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                :class="openGudang ? 'bg-orange-500' : 'hover:bg-blue-800'">

                <span>
                    <x-heroicon-o-archive-box class="w-5 h-5" />
                </span>

                <span x-show="sidebarOpen" x-transition>
                    Gudang Logistik
                </span>
            </div>

            <div x-show="openGudang" x-transition class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">

                <a href="{{ route('admin.jenis-barang.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('admin.jenis-barang.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Jenis Barang
                </a>

                <a href="{{ route('admin.sumber-barang.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('admin.sumber-barang.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Sumber Barang
                </a>

                <a href="{{ route('admin.barang.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('admin.barang.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Data Barang
                </a>

                <a href="{{ route('admin.barang-masuk.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('admin.barang-masuk.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Barang Masuk
                </a>

            </div>
        </div>


        {{-- <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span><x-heroicon-o-truck class="w-5 h-5" /></span>
            <span x-show="sidebarOpen" x-transition>Distribusi Bantuan</span>
        </a> --}}

        <!-- ================= MANAGEMEN POSKO ================= -->

        <!-- KATEGORI BENCANA -->
        <a href="{{ route('admin.kategori_bencana.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span><x-heroicon-o-squares-2x2 class="w-5 h-5" /></span>
            <span x-show="sidebarOpen" x-transition>Kategori Bencana</span>
        </a>

        <!-- DATA BENCANA -->
        <a href="{{ route('admin.bencana.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
        {{ request()->routeIs('admin.bencana.*') ? 'bg-blue-700' : 'hover:bg-blue-800' }}">
            <span>
                <x-heroicon-o-fire class="w-5 h-5" />
            </span>

            <span x-show="sidebarOpen" x-transition>
                Data Bencana
            </span>
        </a>

        <!-- KATEGORI BANTUAN -->
        <a href="{{ route('admin.kategori_bantuan.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
        {{ request()->routeIs('admin.kategori_bantuan.*') ? 'bg-blue-700' : 'hover:bg-blue-800' }}">

            <span>
                <x-heroicon-o-cube class="w-5 h-5" />
            </span>

            <span x-show="sidebarOpen" x-transition>
                Kategori Bantuan
            </span>
        </a>

        <!-- DATA GUDANG -->
        <a href="{{ route('admin.gudang.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded 
            {{ request()->routeIs('gudang.*') ? 'bg-blue-700' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-building-storefront class="w-5 h-5" />

            <span x-show="sidebarOpen">
                Data Gudang
            </span>
        </a>

        <!-- STOK GUDANG -->
        <a href="{{ route('admin.stok_gudang.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded 
                    {{ request()->routeIs('stok.*') ? 'bg-blue-700' : 'hover:bg-blue-800' }}">

            <span>
                <x-heroicon-o-archive-box-arrow-down class="w-5 h-5" />
            </span>

            <span x-show="sidebarOpen">
                Stok Gudang
            </span>
        </a>


      <a href="{{ route('admin.desa.index') }}"
    class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
    {{ request()->routeIs('admin.desa.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

    <x-heroicon-o-map class="w-5 h-5" />

    <span x-show="sidebarOpen" x-transition>
        Data Desa
    </span>
</a>

<a href="{{ route('admin.warga.index') }}"
    class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
    {{ request()->routeIs('admin.warga.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

    <x-heroicon-o-users class="w-5 h-5" />

    <span x-show="sidebarOpen" x-transition>
        Data Warga Terdampak
    </span>
</a>


        @if (auth()->user()->hasRole('admin'))
            <div x-data="{ openMenu: {{ request()->routeIs('admin.management_posko.*') ? 'true' : 'false' }} }" class="rounded">
                <div @click="openMenu = !openMenu"
                    class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                    :class="openMenu ? 'bg-orange-500' : 'hover:bg-blue-800'">

                    <span>
                        <x-heroicon-o-home-modern class="w-5 h-5" />
                    </span>

                    <span x-show="sidebarOpen" x-transition>
                        Manajemen Posko
                    </span>
                </div>

                <div x-show="openMenu" x-transition class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">

                    <a href="{{ route('admin.management_posko.posko.index') }}"
                        class="block px-3 py-2 text-sm rounded {{ request()->routeIs('admin.management_posko.posko.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                        Olah Data Posko
                    </a>

                    <a href="{{ route('admin.management_posko.dapur_umum.index') }}"
                        class="block px-3 py-2 text-sm rounded {{ request()->routeIs('admin.management_posko.dapur_umum.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                        Dapur Umum
                    </a>

                </div>
            </div>
        @elseif(auth()->user()->hasRole('relawan'))
            <div x-data="{ openMenu: {{ request()->routeIs('relawan.management_posko.*') || request()->routeIs('relawan.dapur_umum.*') ? 'true' : 'false' }} }" class="rounded">

                <div @click="openMenu = !openMenu"
                    class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                    :class="openMenu ? 'bg-orange-500' : 'hover:bg-blue-800'">
                    <span>
                        <x-heroicon-o-home-modern class="w-5 h-5" />
                    </span>
                    <span x-show="sidebarOpen" x-transition>
                        Informasi Posko
                    </span>
                </div>

                <div x-show="openMenu" x-transition class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">
                    <a href="{{ route('relawan.management_posko.posko.index') }}"
                        class="block px-3 py-2 text-sm rounded {{ request()->routeIs('relawan.management_posko.posko.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                        Data Posko
                    </a>
                    <a href="{{ route('relawan.management_posko.dapur_umum.index') }}"
                        class="block px-3 py-2 text-sm rounded {{ request()->routeIs('relawan.management_posko.dapur_umum.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                        Dapur Umum
                    </a>
                </div>
            </div>
        @elseif(auth()->user()->hasRole('kadus'))
            <div x-data="{ openMenu: {{ request()->routeIs('kadus.management_posko.*') || request()->routeIs('kadus.dapur_umum.*') ? 'true' : 'false' }} }" class="rounded">

                <div @click="openMenu = !openMenu"
                    class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                    :class="openMenu ? 'bg-orange-500' : 'hover:bg-blue-800'">
                    <span>
                        <x-heroicon-o-home-modern class="w-5 h-5" />
                    </span>
                    <span x-show="sidebarOpen" x-transition>
                        Informasi Posko
                    </span>
                </div>

                <div x-show="openMenu" x-transition class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">
                    <a href="{{ route('kadus.management_posko.posko.index') }}"
                        class="block px-3 py-2 text-sm rounded {{ request()->routeIs('kadus.management_posko.posko.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                        Data Posko
                    </a>
                    <a href="{{ route('kadus.management_posko.dapur_umum.index') }}"
                        class="block px-3 py-2 text-sm rounded {{ request()->routeIs('kadus.management_posko.dapur_umum.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                        Dapur Umum
                    </a>
                </div>
            </div>
        @elseif(auth()->user()->hasRole('kabid'))
            <div x-data="{ openMenu: {{ request()->routeIs('kabid.management_posko.*') || request()->routeIs('kabid.dapur_umum.*') ? 'true' : 'false' }} }" class="rounded">

                <div @click="openMenu = !openMenu"
                    class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                    :class="openMenu ? 'bg-orange-500' : 'hover:bg-blue-800'">
                    <span>
                        <x-heroicon-o-home-modern class="w-5 h-5" />
                    </span>
                    <span x-show="sidebarOpen" x-transition>
                        Informasi Posko
                    </span>
                </div>

                <div x-show="openMenu" x-transition class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">
                    <a href="{{ route('kabid.management_posko.posko.index') }}"
                        class="block px-3 py-2 text-sm rounded {{ request()->routeIs('kabid.management_posko.posko.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                        Data Posko
                    </a>
                    <a href="{{ route('kabid.management_posko.dapur_umum.index') }}"
                        class="block px-3 py-2 text-sm rounded {{ request()->routeIs('kabid.management_posko.dapur_umum.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                        Dapur Umum
                    </a>
                </div>
            </div>
        @elseif(auth()->user()->hasRole('desa'))
            <div x-data="{ openMenu: {{ request()->routeIs('desa.management_posko.*') || request()->routeIs('desa.dapur_umum.*') ? 'true' : 'false' }} }" class="rounded">

                <div @click="openMenu = !openMenu"
                    class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                    :class="openMenu ? 'bg-orange-500' : 'hover:bg-blue-800'">
                    <span>
                        <x-heroicon-o-home-modern class="w-5 h-5" />
                    </span>
                    <span x-show="sidebarOpen" x-transition>
                        Informasi Posko
                    </span>
                </div>

                <div x-show="openMenu" x-transition class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">
                    <a href="{{ route('desa.management_posko.posko.index') }}"
                        class="block px-3 py-2 text-sm rounded {{ request()->routeIs('desa.management_posko.posko.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                        Data Posko
                    </a>
                    <a href="{{ route('desa.management_posko.dapur_umum.index') }}"
                        class="block px-3 py-2 text-sm rounded {{ request()->routeIs('desa.management_posko.dapur_umum.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                        Dapur Umum
                    </a>
                </div>
            </div>
        @endif

        <!-- ================= MANAGEMENT BARANG ================= -->

        <div x-data="{ openMenuManagementBarang: {{ request()->routeIs('admin.management_barang.*') ? 'true' : 'false' }} }" class="rounded">
            <div @click="openMenuManagementBarang = !openMenuManagementBarang"
                class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                :class="openMenuManagementBarang
                    ?
                    'bg-orange-500' :
                    'hover:bg-blue-800'">
                <span>
                    <x-heroicon-o-truck class="w-5 h-5" />
                </span>
                <span x-show="sidebarOpen" x-transition>
                    Manajemen Barang
                </span>
            </div>

            <div x-show="openMenuManagementBarang" x-transition
                class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">

                <a href="{{ route('admin.management_barang.petugas.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('admin.management_barang.petugas.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Petugas
                </a>
                <a href="{{ route('admin.management_barang.pengambilan.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('admin.management_barang.pengambilan.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Pengambilan Barang
                </a>


                <a href="{{ route('admin.management_barang.pengembalian.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('admin.management_barang.pengembalian.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Pengembalian Barang
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

                <a href="{{ route('admin.management_distribusi.distribusi.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('admin.management_distribusi.distribusi.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Distribusi
                </a>
                <a href="{{ route('admin.management_distribusi.penerima.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('admin.management_distribusi.penerima_distribusi.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Penerima Distribusi
                </a>
                <a href="{{ route('admin.management_distribusi.paket_bantuan.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('admin.management_distribusi.paket_bantuan.*') || request()->routeIs('admin.management_distribusi.detail_paket.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Paket Bantuan
                </a>
                <a href="{{ route('admin.management_distribusi.distribusi_paket.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('admin.management_distribusi.distribusi_paket.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Distribusi Pasca Bencana
                </a>


            </div>
        </div>

        <!-- ================= DATA KORBAN ================= -->
        <a href="{{ route('admin.management_korban.korban.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded 
        {{ request()->routeIs('admin.management_korban.korban.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <span>
                <x-heroicon-o-user-group class="w-5 h-5" />
            </span>

            <span x-show="sidebarOpen" x-transition>
                Data Korban
            </span>
        </a>

        <!-- ================= JADWAL LAYANAN PASCA BENCANA  ================= -->
        @if (auth()->user()->hasRole('admin'))
            <a href="{{ route('admin.jadwal.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
                {{ request()->routeIs('admin.jadwal.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">
                <span><x-heroicon-o-clock class="w-5 h-5" /></span>
                <span x-show="sidebarOpen" x-transition>Jadwal Layanan Pasca Bencana</span>
            </a>
        @elseif(auth()->user()->hasRole('relawan'))
            <a href="{{ route('relawan.jadwal.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
                {{ request()->routeIs('relawan.jadwal.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">
                <span><x-heroicon-o-clock class="w-5 h-5" /></span>
                <span x-show="sidebarOpen" x-transition>Jadwal Layanan Pasca Bencana</span>
            </a>
        @elseif(auth()->user()->hasRole('kadus'))
            <a href="{{ route('kadus.jadwal.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
                {{ request()->routeIs('kadus.jadwal.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">
                <span><x-heroicon-o-clock class="w-5 h-5" /></span>
                <span x-show="sidebarOpen" x-transition>Jadwal Layanan Pasca Bencana</span>
            </a>
        @elseif(auth()->user()->hasRole('kabid'))
            <a href="{{ route('kabid.jadwal.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
                {{ request()->routeIs('kabid.jadwal.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">
                <span><x-heroicon-o-clock class="w-5 h-5" /></span>
                <span x-show="sidebarOpen" x-transition>Jadwal Layanan Pasca Bencana</span>
            </a>
        @elseif(auth()->user()->hasRole('desa'))
            <a href="{{ route('desa.jadwal.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
                {{ request()->routeIs('desa.jadwal.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">
                <span><x-heroicon-o-clock class="w-5 h-5" /></span>
                <span x-show="sidebarOpen" x-transition>Jadwal Layanan Pasca Bencana</span>
            </a>
        @elseif(auth()->user()->hasRole('ketua_tim'))
            <a href="{{ route('ketua_tim.jadwal.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
                {{ request()->routeIs('ketua_tim.jadwal.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">
                <span><x-heroicon-o-clock class="w-5 h-5" /></span>
                <span x-show="sidebarOpen" x-transition>Jadwal Layanan Pasca Bencana</span>
            </a>
        @elseif(auth()->user()->hasRole('petugas'))
            <a href="{{ route('petugas.jadwal.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
                {{ request()->routeIs('petugas.jadwal.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">
                <span><x-heroicon-o-clock class="w-5 h-5" /></span>
                <span x-show="sidebarOpen" x-transition>Jadwal Layanan Pasca Bencana</span>
            </a>
        @elseif(auth()->user()->hasRole('pegawai'))
            <a href="{{ route('pegawai.jadwal.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
                {{ request()->routeIs('pegawai.jadwal.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">
                <span><x-heroicon-o-clock class="w-5 h-5" /></span>
                <span x-show="sidebarOpen" x-transition>Jadwal Layanan Pasca Bencana</span>
            </a>
        @endif
        <!-- ================= WARGA ================= -->
        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span>👥</span>
            <span x-show="sidebarOpen" x-transition>Warga</span>
        </a>

        <!-- ================= MANAJEMEN PEGAWAI ================= -->
        <div x-data="{ openMenuPegawai: {{ request()->routeIs('admin.management_pegawai.*') ? 'true' : 'false' }} }" class="rounded">

            <div @click="openMenuPegawai = !openMenuPegawai"
                class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                :class="openMenuPegawai ? 'bg-orange-500' : 'hover:bg-blue-800'">

                <span>
                    <x-heroicon-o-users class="w-5 h-5" />
                </span>

                <span x-show="sidebarOpen" x-transition>
                    Manajemen Pegawai
                </span>
            </div>

            <div x-show="openMenuPegawai" x-transition class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">

                <a href="{{ route('admin.management_pegawai.pegawai.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('admin.management_pegawai.pegawai.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Data Pegawai
                </a>

                <a href="{{ route('admin.management_pegawai.relawan.index') }}"
                    class="block px-3 py-2 text-sm rounded {{ request()->routeIs('admin.management_pegawai.relawan.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">
                    Data Relawan
                </a>
            </div>
        </div>

        {{-- Laporan Admin --}}
        @if (auth()->user()->hasRole('admin'))
            <div x-data="{ openMenuLaporan: {{ request()->routeIs('admin.laporan.*') ? 'true' : 'false' }} }" class="rounded">

                <div @click="openMenuLaporan = !openMenuLaporan"
                    class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                    :class="openMenuLaporan ? 'bg-orange-500' : 'hover:bg-blue-800'">

                    <span>
                        <x-heroicon-o-document-text class="w-5 h-5" />
                    </span>

                    <span x-show="sidebarOpen" x-transition>
                        Laporan
                    </span>

                </div>


                <div x-show="openMenuLaporan" x-transition class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">

                    <a href="{{ route('admin.laporan.index') }}"
                        class="block px-3 py-2 text-sm rounded
            {{ request()->routeIs('admin.laporan.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">

                        Data Laporan
                    </a>

                </div>

            </div>
        @endif



        {{-- Laporan Kabid --}}
        @if (auth()->user()->hasRole('kabid'))
            <div x-data="{ openMenuLaporanKabid: {{ request()->routeIs('kabid.laporan.*') ? 'true' : 'false' }} }" class="rounded">

                <div @click="openMenuLaporanKabid = !openMenuLaporanKabid"
                    class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                    :class="openMenuLaporanKabid ? 'bg-orange-500' : 'hover:bg-blue-800'">

                    <span>
                        <x-heroicon-o-document-text class="w-5 h-5" />
                    </span>

                    <span x-show="sidebarOpen" x-transition>
                        Laporan
                    </span>

                </div>


                <div x-show="openMenuLaporanKabid" x-transition
                    class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">


                    <a href="{{ route('kabid.laporan.index') }}"
                        class="block px-3 py-2 text-sm rounded
            {{ request()->routeIs('kabid.laporan.*') ? 'bg-white/10' : 'hover:bg-blue-700' }}">

                        Data Laporan
                    </a>


                </div>

            </div>
        @endif

    </nav>

</aside>
