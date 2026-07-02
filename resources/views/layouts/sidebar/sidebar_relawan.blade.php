<aside class="bg-[#1E3A8A] text-white h-screen fixed left-0 top-0 w-64 overflow-y-auto transition-all duration-200"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    {{-- Logo --}}
    <div class="p-4 flex items-center justify-between">

        <div x-show="sidebarOpen" x-transition class="flex items-center gap-3">

            <img src="{{ asset('logo-dinsos.png') }}" class="w-10 h-10">

            <div>

                <h1 class="font-bold">

                    Dinas Sosial

                </h1>

                <p class="text-xs text-gray-300">

                    Kabupaten Cilacap

                </p>

            </div>

        </div>

    </div>

    {{-- MENU --}}
    <nav class="mt-4 space-y-2 px-2 pb-12">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('dashboard') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-home class="w-5 h-5" />

            <span x-show="sidebarOpen">

                Dashboard

            </span>

        </a>

        {{-- Pengaduan --}}
        <a href="{{ route('user.pengaduan.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('user.pengaduan.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-exclamation-triangle class="w-5 h-5" />

            <span x-show="sidebarOpen">

                Pengaduan Bencana

            </span>

        </a>

        {{-- Data Bencana --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('relawan.bencana.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-globe-alt class="w-5 h-5" />

            <span x-show="sidebarOpen">

                Data Bencana

            </span>

        </a>

        {{-- Data Pengungsi --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('relawan.pengungsi.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-home-modern class="w-5 h-5" />

            <span x-show="sidebarOpen">

                Data Pengungsi

            </span>

        </a>

        {{-- Data Korban --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('relawan.korban.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-users class="w-5 h-5" />

            <span x-show="sidebarOpen">

                Data Korban

            </span>

        </a>
        {{-- Distribusi Bantuan --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('relawan.distribusi.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-truck class="w-5 h-5" />

            <span x-show="sidebarOpen">

                Distribusi Bantuan

            </span>

        </a>

        {{-- Data Penerima Bantuan --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('relawan.penerima.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-gift class="w-5 h-5" />

            <span x-show="sidebarOpen">

                Penerima Bantuan

            </span>

        </a>

        {{-- Pengambilan Barang --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('relawan.pengambilan.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-arrow-up-tray class="w-5 h-5" />

            <span x-show="sidebarOpen">

                Pengambilan Barang

            </span>

        </a>

        {{-- Pengembalian Barang --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('relawan.pengembalian.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-arrow-uturn-left class="w-5 h-5" />

            <span x-show="sidebarOpen">

                Pengembalian Barang

            </span>

        </a>
        {{-- Data Anak Terpisah --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('relawan.anak.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-user-group class="w-5 h-5" />

            <span x-show="sidebarOpen">

                Data Anak Terpisah

            </span>

        </a>

        {{-- Penjemputan Anak --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('relawan.penjemputan.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-hand-raised class="w-5 h-5" />

            <span x-show="sidebarOpen">

                Penjemputan Anak

            </span>

        </a>

        {{-- Jadwal Layanan --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('relawan.jadwal.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-calendar-days class="w-5 h-5" />

            <span x-show="sidebarOpen">

                Jadwal Layanan

            </span>

        </a>

    </nav>

</aside>
