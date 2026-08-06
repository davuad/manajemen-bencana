<aside class="bg-[#1E3A8A] text-white h-screen fixed left-0 top-0 w-64 overflow-y-auto transition-all duration-200"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    {{-- Logo --}}
    <div class="p-4 flex items-center justify-between">
        <div x-show="sidebarOpen" x-transition class="flex items-center gap-3">
            <img src="{{ asset('logo-dinsos.png') }}" alt="Logo Dinsos" class="w-10 h-10">
            <div>
                <h1 class="font-bold">Dinas Sosial</h1>
                <p class="text-xs text-gray-300">
                    Kabupaten Cilacap
                </p>
            </div>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="mt-4 space-y-2 px-2 pb-12">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('dashboard') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <span>
                <x-heroicon-o-home class="w-5 h-5" />
            </span>

            <span x-show="sidebarOpen" x-transition>
                Dashboard
            </span>

        </a>

        {{-- Verifikasi Pengaduan --}}
        <a href="{{ route('kabid.pengaduan.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('kabid.pengaduan.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <span>
                <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
            </span>

            <span x-show="sidebarOpen" x-transition>
                Verifikasi Pengaduan
            </span>

        </a>

        {{-- Data Desa --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('kabid.desa.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <span>
                <x-heroicon-o-map class="w-5 h-5" />
            </span>

            <span x-show="sidebarOpen" x-transition>
                Data Desa
            </span>

        </a>

        {{-- Data Warga --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('kabid.warga.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <span>
                <x-heroicon-o-users class="w-5 h-5" />
            </span>

            <span x-show="sidebarOpen" x-transition>
                Data Warga Terdampak
            </span>

        </a>

        {{-- Informasi Posko --}}
        <div
            x-data="{ openPosko: {{ request()->routeIs('management_posko.*') ? 'true' : 'false' }} }"
            class="rounded">

            <div
                @click="openPosko = !openPosko"
                class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                :class="openPosko ? 'bg-orange-500' : 'hover:bg-blue-800'">

                <span>
                    <x-heroicon-o-home-modern class="w-5 h-5" />
                </span>

                <span x-show="sidebarOpen" x-transition>
                    Informasi Posko
                </span>

            </div>

            <div
                x-show="openPosko"
                x-transition
                class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">

                <a href="#"
                    class="block px-3 py-2 text-sm rounded
                    {{ request()->routeIs('management_posko.posko.*') ? 'bg-white/10 text-orange-400 font-semibold' : 'hover:bg-blue-700' }}">

                    Data Posko

                </a>

                <a href="#"
                    class="block px-3 py-2 text-sm rounded
                    {{ request()->routeIs('management_posko.dapur_umum.*') ? 'bg-white/10 text-orange-400 font-semibold' : 'hover:bg-blue-700' }}">

                    Dapur Umum

                </a>

            </div>

        </div>

        {{-- Laporan --}}
        <div
            x-data="{ openLaporan: {{ request()->routeIs('kabid.laporan.*') ? 'true' : 'false' }} }"
            class="rounded">

            <div
                @click="openLaporan = !openLaporan"
                class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                :class="openLaporan ? 'bg-orange-500' : 'hover:bg-blue-800'">

                <span>
                    <x-heroicon-o-document-text class="w-5 h-5"/>
                </span>

                <span x-show="sidebarOpen" x-transition>
                    Laporan
                </span>

            </div>

            <div
                x-show="openLaporan"
                x-transition
                class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">

                <a
                    href="#"
                    class="block px-3 py-2 text-sm rounded
                    {{ request()->routeIs('kabid.laporan.*')
                        ? 'bg-white/10 text-orange-400 font-semibold'
                        : 'hover:bg-blue-700' }}">

                    Data Laporan

                </a>

            </div>

        </div>

    </nav>

</aside>