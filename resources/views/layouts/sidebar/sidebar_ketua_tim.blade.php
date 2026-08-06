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

        {{-- DASHBOARD --}}
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

        {{-- MONITORING PENGADUAN --}}
        <a href="{{ route('ketua_tim.pengaduan.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('ketua_tim.pengaduan.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <span>
                <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
            </span>

            <span x-show="sidebarOpen" x-transition>
                Monitoring Pengaduan
            </span>

        </a>
        {{-- DATA DESA --}}
        <a href="{{ route('ketua_tim.desa.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('ketua_tim.desa.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <span>
                <x-heroicon-o-map class="w-5 h-5" />
            </span>

            <span x-show="sidebarOpen" x-transition>
                Data Desa
            </span>

        </a>

        {{-- DATA WARGA TERDAMPAK --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('ketua_tim.warga.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <span>
                <x-heroicon-o-users class="w-5 h-5" />
            </span>

            <span x-show="sidebarOpen" x-transition>
                Data Warga Terdampak
            </span>

        </a>

        {{-- INFORMASI POSKO --}}
        <div x-data="{ openPosko: {{ request()->routeIs('management_posko.*') ? 'true' : 'false' }} }" class="rounded">

            <div @click="openPosko = !openPosko"
                class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                :class="openPosko ? 'bg-orange-500' : 'hover:bg-blue-800'">

                <span>
                    <x-heroicon-o-home-modern class="w-5 h-5" />
                </span>

                <span x-show="sidebarOpen" x-transition>
                    Informasi Posko
                </span>

            </div>

            <div x-show="openPosko" x-transition class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">

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

        {{-- DATA KORBAN --}}
        <div x-data="{ openKorban: {{ request()->routeIs('ketua_tim.korban.*') ? 'true' : 'false' }} }" class="rounded">

            <div @click="openKorban = !openKorban"
                class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                :class="openKorban ? 'bg-orange-500' : 'hover:bg-blue-800'">

                <span>
                    <x-heroicon-o-user-group class="w-5 h-5" />
                </span>

                <span x-show="sidebarOpen" x-transition>
                    Data Korban
                </span>

            </div>

            <div x-show="openKorban" x-transition class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">

                <a href="#"
                    class="block px-3 py-2 text-sm rounded
                    {{ request()->routeIs('ketua_tim.korban.*') ? 'bg-white/10 text-orange-400 font-semibold' : 'hover:bg-blue-700' }}">

                    Data Korban

                </a>

            </div>

        </div>

        {{-- JADWAL LAYANAN --}}
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded transition-all duration-200
            {{ request()->routeIs('ketua_tim.jadwal.*') ? 'bg-orange-500' : 'hover:bg-blue-800' }}">

            <span>
                <x-heroicon-o-clock class="w-5 h-5" />
            </span>

            <span x-show="sidebarOpen" x-transition>
                Jadwal Layanan
            </span>

        </a>
        {{-- LAPORAN --}}
        <div x-data="{ openLaporan: {{ request()->routeIs('ketua_tim.laporan.*') ? 'true' : 'false' }} }" class="rounded">

            <div @click="openLaporan = !openLaporan"
                class="flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition-all duration-200"
                :class="openLaporan ? 'bg-orange-500' : 'hover:bg-blue-800'">

                <span>
                    <x-heroicon-o-document-text class="w-5 h-5" />
                </span>

                <span x-show="sidebarOpen" x-transition>
                    Laporan
                </span>

            </div>

            <div x-show="openLaporan" x-transition class="ml-2 mt-1 rounded bg-blue-800 overflow-hidden p-2">

                <a href="#"
                    class="block px-3 py-2 text-sm rounded
                    {{ request()->routeIs('ketua_tim.laporan.*')
                        ? 'bg-white/10 text-orange-400 font-semibold'
                        : 'hover:bg-blue-700' }}">

                    Data Laporan

                </a>

            </div>

        </div>

    </nav>

</aside>
