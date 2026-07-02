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

        {{-- PENGADUAN --}}
        <a href="{{ route('user.pengaduan.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
    {{ request()->routeIs('user.pengaduan.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-exclamation-triangle class="w-5 h-5" />

            <span x-show="sidebarOpen">
                Pengaduan Bencana
            </span>

        </a>

        {{-- DATA DESA --}}
        <a href="{{ route('desa.desa.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('desa.desa.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-building-office-2 class="w-5 h-5" />

            <span x-show="sidebarOpen">
                Data Desa
            </span>

        </a>

        {{-- WARGA TERDAMPAK --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('desa.warga.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-users class="w-5 h-5" />

            <span x-show="sidebarOpen">
                Warga Terdampak
            </span>

        </a>

        {{-- PENGUNGSI --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('desa.pengungsi.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-home-modern class="w-5 h-5" />

            <span x-show="sidebarOpen">
                Registrasi Pengungsi
            </span>

        </a>

        {{-- PENGAJUAN BANTUAN --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('desa.pengajuan.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-inbox-arrow-down class="w-5 h-5" />

            <span x-show="sidebarOpen">
                Pengajuan Bantuan
            </span>

        </a>

        {{-- DISTRIBUSI --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('desa.distribusi.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-truck class="w-5 h-5" />

            <span x-show="sidebarOpen">
                Distribusi Bantuan
            </span>

        </a>

        {{-- LAPORAN --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('desa.laporan.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-document-chart-bar class="w-5 h-5" />

            <span x-show="sidebarOpen">
                Laporan
            </span>

        </a>

        {{-- JADWAL --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('desa.jadwal.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <x-heroicon-o-calendar-days class="w-5 h-5" />

            <span x-show="sidebarOpen">
                Jadwal Layanan
            </span>

        </a>

    </nav>

</aside>
