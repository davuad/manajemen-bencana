@extends('layouts.app')

@section('content')
    {{-- ========================================================================= --}}
    {{-- LOGIKA BARU: DETEKSI HAK AKSES MURNI BERDASARKAN PREFIX URL (BEBAS LOGIN) --}}
    {{-- ========================================================================= --}}
    @php
        // 1. Tentukan prefix default
        $prefix = 'admin'; 
        
        // 2. Baca URL aktif, jika mengandung kata role tertentu, set prefix-nya
        if (request()->routeIs('kabid.*') || request()->is('kabid/*')) {
            $prefix = 'kabid';
        } elseif (request()->routeIs('relawan.*') || request()->is('relawan/*')) {
            $prefix = 'relawan';
        } elseif (request()->routeIs('kadus.*') || request()->is('kadus/*')) {
            $prefix = 'kadus';
        } elseif (request()->routeIs('desa.*') || request()->is('desa/*')) {
            $prefix = 'desa';
        } elseif (request()->routeIs('ketua_tim.*') || request()->is('ketua_tim/*')) {
            $prefix = 'ketua_tim';
        } elseif (request()->routeIs('petugas.*') || request()->is('petugas/*')) {
            $prefix = 'petugas';
        } elseif (request()->routeIs('pegawai.*') || request()->is('pegawai/*')) {
            $prefix = 'pegawai';
        }

        // 3. Buat variabel hak akses halaman (Boolean)
        $isAdminPage = request()->routeIs('admin.*') || request()->is('admin/*');
        $isKabidPage = request()->routeIs('kabid.*') || request()->is('kabid/*');
    @endphp

    <div class="bg-white rounded-xl shadow p-6 m-3 mt-5">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Jadwal Layanan Pasca Bencana</h2>

            <div class="flex items-center gap-3">
                {{-- [USER REQUIREMENT]: TOMBOL CETAK PDF MUNCUL DI URL ADMIN & KABID --}}
                @if($isAdminPage || $isKabidPage)
                    <a href="{{ route($prefix . '.jadwal.cetak', request()->all()) }}" target="_blank"
                        class="flex items-center gap-2 bg-white border border-blue-200 text-blue-600 hover:bg-red-50 px-4 py-2 rounded-lg transition font-black text-[10px] shadow-sm uppercase tracking-widest">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak PDF
                    </a>
                @endif

                {{-- [USER REQUIREMENT]: TOMBOL TAMBAH JADWAL HANYA MUNCUL DI URL ADMIN --}}
                @if($isAdminPage)
                    <a href="{{ route('admin.jadwal.create') }}"
                        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition font-black text-[10px] shadow-lg shadow-indigo-100 uppercase tracking-widest">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Jadwal
                    </a>
                @endif
            </div>
        </div>

        {{-- Filter Form (Otomatis ganti action sesuai prefix rute URL yang aktif) --}}
        <form method="GET" action="{{ route($prefix . '.jadwal.index') }}" class="flex flex-wrap items-center gap-2 mb-6 p-4 bg-gray-50 rounded-lg border border-gray-100">
            <select name="bencana_id" class="text-[11px] p-2 border border-gray-200 rounded-lg bg-white w-48">
                <option value="">SEMUA BENCANA</option>
                @foreach ($bencanas as $b)
                    <option value="{{ $b->id }}" {{ request('bencana_id') == $b->id ? 'selected' : '' }}>
                        {{ $b->nama_bencana }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="text-[11px] p-2 border border-gray-200 rounded-lg bg-white w-40">
                <option value="">SEMUA STATUS</option>
                <option value="dijadwalkan" {{ request('status') == 'dijadwalkan' ? 'selected' : '' }}>DIJADWALKAN</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>SELESAI</option>
            </select>

            <select name="tahun" class="text-[11px] p-2 border border-gray-200 rounded-lg bg-white w-40">
                <option value="">SEMUA TAHUN</option>
                @foreach ($tahunTersedia as $tahun)
                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                        {{ $tahun }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition">
                Filter
            </button>
            <a href="{{ route($prefix . '.jadwal.index') }}" class="text-[10px] text-gray-400 font-bold hover:text-gray-600 uppercase tracking-widest ml-2">
                Reset
            </a>
        </form>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-50 text-green-700 rounded-lg border border-green-200 text-xs font-bold uppercase">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Data Table --}}
        <div class="overflow-x-auto border border-gray-100 rounded-lg">
            <table class="w-full text-xs text-left min-w-[1200px]">
                <thead class="bg-gray-50 text-gray-600 uppercase font-black border-b border-gray-200">
                    <tr>
                        <th class="p-4 text-center w-12">No</th>
                        <th class="p-4 text-left min-w-[260px]">Nama Bencana</th>
                        <th class="p-4 text-left min-w-[160px]">Jenis Layanan</th>
                        <th class="p-4 text-left min-w-[160px]">Sasaran</th>
                        <th class="p-4 text-left min-w-[160px]">Pegawai Dinsos</th>
                        <th class="p-4 text-left min-w-[160px]">Petugas Kesehatan</th>
                        <th class="p-4 text-center">Tanggal</th>
                        <th class="p-4 text-center min-w-[160px]">Waktu</th>
                        <th class="p-4 text-left min-w-[160px]">Lokasi Layanan</th>
                        <th class="p-4 text-center">Status</th>
                        
                        {{-- [USER REQUIREMENT]: HEADER AKSI HANYA MUNCUL DI URL ADMIN --}}
                        @if($isAdminPage)
                            <th class="p-4 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($jadwals as $key => $jadwal)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 text-center text-gray-700 font-bold">{{ $key + 1 }}</td>
                            <td class="p-4 text-left text-gray-700 font-medium">
                                {{ $jadwal->bencana->nama_bencana ?? 'BENCANA' }} - 
                                {{ $jadwal->bencana->desa->nama_desa ?? 'DESA ?' }} - 
                                {{ $jadwal->bencana ? \Carbon\Carbon::parse($jadwal->bencana->tanggal)->format('Y') : '-' }}
                            </td>
                            <td class="p-4 text-left text-gray-700 font-medium">{{ $jadwal->jenis_layanan }}</td>
                            <td class="p-4 text-left text-gray-700 font-medium">{{ $jadwal->sarana }}</td>
                            <td class="p-4 text-left text-gray-700 font-medium">{{ $jadwal->pegawai->nama_pegawai ?? '-' }}</td>
                            <td class="p-4 text-left text-gray-700 font-medium">{{ $jadwal->petugas_lapangan }}</td>
                            <td class="p-4 text-center text-gray-700 font-bold">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal_layanan)->format('d/m/Y') }}
                            </td>
                            <td class="p-4 text-center text-gray-700 font-medium">
                                {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }} WIB
                            </td>
                            <td class="p-4 text-left text-gray-700 font-medium">{{ $jadwal->lokasi_layanan }}</td>
                            <td class="p-4 text-center">
                                @if ($jadwal->status == 'selesai')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-md font-bold text-[9px] tracking-widest uppercase border border-emerald-200">SELESAI</span>
                                @else
                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded-md font-bold text-[9px] tracking-widest uppercase border border-blue-200">DIJADWALKAN</span>
                                @endif
                            </td>

                            {{-- [USER REQUIREMENT]: TOMBOL EDIT DAN HAPUS HANYA MUNCUL DI URL ADMIN --}}
                            @if($isAdminPage)
                                <td class="p-4 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition border border-transparent hover:border-blue-200">
                                            <x-heroicon-o-pencil-square class="w-4 h-4" />
                                        </a>

                                        @php
                                            $namaBencana = $jadwal->bencana->nama_bencana ?? 'Bencana';
                                            $namaDesa = $jadwal->bencana->desa->nama_desa ?? 'Desa';
                                            $tahunBencana = $jadwal->bencana ? \Carbon\Carbon::parse($jadwal->bencana->tanggal)->format('Y') : '-';
                                            $teksHapus = addslashes("$namaBencana - $namaDesa - $tahunBencana");
                                        @endphp

                                        <button type="button" onclick="openModal('{{ $jadwal->id }}', '{{ $teksHapus }}')" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition border border-transparent hover:border-red-200">
                                            <x-heroicon-o-trash class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isAdminPage ? '11' : '10' }}" class="text-center p-12 text-gray-400 italic">Belum ada jadwal layanan yang diinput.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Section --}}
    <div id="deleteModal" class="fixed inset-0 backdrop-blur-sm bg-black/10 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">
            <div class="flex items-start gap-3">
                <div class="bg-red-100 p-2 rounded-full">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-500" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Hapus Jadwal</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Yakin ingin menghapus jadwal <span id="namaJadwal" class="font-bold text-red-600"></span> ?
                    </p>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">Batal</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id, nama) {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('namaJadwal').innerText = `"${nama}"`;
            let url = "{{ route('admin.jadwal.destroy', ':id') }}";
            url = url.replace(':id', id);
            document.getElementById('deleteForm').action = url;
        }

        function closeModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
@endsection