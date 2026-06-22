@extends('layouts.app')

@section('content')
    @php
        $prefix = 'admin'; 
        if (request()->routeIs('kabid.*') || request()->is('kabid/*')) $prefix = 'kabid';
        elseif (request()->routeIs('relawan.*') || request()->is('relawan/*')) $prefix = 'relawan';
        elseif (request()->routeIs('kadus.*') || request()->is('kadus/*')) $prefix = 'kadus';
        elseif (request()->routeIs('desa.*') || request()->is('desa/*')) $prefix = 'desa';
        elseif (request()->routeIs('ketua_tim.*') || request()->is('ketua_tim/*')) $prefix = 'ketua_tim';
        elseif (request()->routeIs('petugas.*') || request()->is('petugas/*')) $prefix = 'petugas';
        elseif (request()->routeIs('pegawai.*') || request()->is('pegawai/*')) $prefix = 'pegawai';

        $isAdminPage = request()->routeIs('admin.*') || request()->is('admin/*');
        $isKabidPage = request()->routeIs('kabid.*') || request()->is('kabid/*');
    @endphp

    <div class="bg-white rounded-xl shadow p-6 m-3 mt-5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Jadwal Layanan Pasca Bencana</h2>
            <div class="flex items-center gap-3">
                @if($isAdminPage || $isKabidPage)
                    <a href="{{ route($prefix . '.jadwal.cetak', request()->all()) }}" target="_blank" class="flex items-center gap-2 bg-white border border-blue-200 text-blue-600 hover:bg-red-50 px-4 py-2 rounded-lg transition font-black text-[10px] shadow-sm uppercase tracking-widest">Cetak PDF</a>
                @endif
                @if($isAdminPage)
                    <a href="{{ route('admin.jadwal.create') }}" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition font-black text-[10px] shadow-lg uppercase tracking-widest">Tambah Jadwal</a>
                @endif
            </div>
        </div>

        {{-- FORM FILTER --}}
        <form method="GET" action="{{ route($prefix . '.jadwal.index') }}" class="flex flex-wrap items-center gap-2 mb-6 p-4 bg-gray-50 rounded-lg border border-gray-100">
            <select name="bencana_id" class="text-[11px] p-2 border border-gray-200 rounded-lg bg-white w-48">
                <option value="">SEMUA BENCANA</option>
                @foreach ($bencanas as $b)
                <option value="{{ $b->id }}" {{ request('bencana_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bencana }}</option>
                @endforeach
            </select>
            <select name="status" class="text-[11px] p-2 border border-gray-200 rounded-lg bg-white w-40">
                <option value="">SEMUA STATUS</option>
                <option value="dijadwalkan" {{ request('status') == 'dijadwalkan' ? 'selected' : '' }}>DIJADWALKAN</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>SELESAI</option>
            </select>
            @if($isAdminPage || $isKabidPage)
                <input type="number" name="tahun_mulai" value="{{ request('tahun_mulai') }}" placeholder="Dari Tahun" class="text-[11px] p-2 border border-gray-200 rounded-lg bg-white w-28">
                <span class="text-xs text-gray-400 font-bold">s/d</span>
                <input type="number" name="tahun_selesai" value="{{ request('tahun_selesai') }}" placeholder="Sampai Tahun" class="text-[11px] p-2 border border-gray-200 rounded-lg bg-white w-28">
            @else
                <select name="tahun" class="text-[11px] p-2 border border-gray-200 rounded-lg bg-white w-32">
                    <option value="">SEMUA TAHUN</option>
                    @foreach ($tahunTersedia as $tahun)
                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endforeach
                </select>
            @endif
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition">Filter</button>
            <a href="{{ route($prefix . '.jadwal.index') }}" class="text-[10px] text-gray-400 font-bold hover:text-gray-600 uppercase tracking-widest ml-2">Reset</a>
        </form>

        {{-- PESAN SUKSES (TAMBAHAN PENTING) --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200 text-xs font-bold uppercase tracking-widest">
                {{ session('success') }}
            </div>
        @endif

        {{-- TABEL --}}
        <div class="overflow-x-auto border border-gray-100 rounded-lg">
             <table class="w-full text-xs text-left min-w-[1200px]">
                <thead class="bg-gray-50 text-gray-600 uppercase font-black border-b border-gray-200">
                    <tr>
                        <th class="p-4 text-center w-12">No</th>
                        <th class="p-4">Nama Bencana</th>
                        <th class="p-4">Jenis Layanan</th>
                        <th class="p-4">Sasaran</th>
                        <th class="p-4">Pegawai Dinsos</th>
                        <th class="p-4">Petugas</th>
                        <th class="p-4 text-center">Tanggal</th>
                        <th class="p-4 text-center">Waktu</th>
                        <th class="p-4">Lokasi</th>
                        <th class="p-4 text-center">Status</th>
                        @if($isAdminPage)<th class="p-4 text-center">Aksi</th>@endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($jadwals as $key => $jadwal)
                    <tr class="hover:bg-gray-50 transition text-gray-700">
                        <td class="p-4 text-center font-bold">{{ ($jadwals->currentPage() - 1) * $jadwals->perPage() + $loop->iteration }}</td>
                        <td class="p-4 font-medium">{{ $jadwal->bencana->nama_bencana ?? 'BENCANA' }} - {{ $jadwal->bencana->desa->nama_desa ?? 'DESA' }} - {{ $jadwal->bencana ? \Carbon\Carbon::parse($jadwal->bencana->tanggal)->format('Y') : '-' }}</td>
                        <td class="p-4">{{ $jadwal->jenis_layanan }}</td>
                        <td class="p-4">{{ $jadwal->sarana }}</td>
                        <td class="p-4">{{ $jadwal->pegawai->nama_pegawai ?? '-' }}</td>
                        <td class="p-4">{{ $jadwal->petugas_lapangan }}</td>
                        <td class="p-4 text-center">{{ \Carbon\Carbon::parse($jadwal->tanggal_layanan)->format('d/m/Y') }}</td>
                        <td class="p-4 text-center">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                        <td class="p-4">{{ $jadwal->lokasi_layanan }}</td>
                        <td class="p-4 text-center">
                            <span class="px-2 py-1 rounded-md font-bold text-[9px] uppercase border {{ $jadwal->status == 'selesai' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-blue-100 text-blue-800 border-blue-200' }}">{{ $jadwal->status }}</span>
                        </td>
                        @if($isAdminPage)
                            <td class="p-4 text-center">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg border border-transparent hover:border-blue-200"><x-heroicon-o-pencil-square class="w-4 h-4" /></a>
                                    <button type="button" onclick="openModal('{{ $jadwal->id }}', '{{ addslashes($jadwal->bencana->nama_bencana ?? Jadwal) }}')" class="p-2 text-red-500 hover:bg-red-50 rounded-lg border border-transparent hover:border-red-200"><x-heroicon-o-trash class="w-4 h-4" /></button>
                                </div>
                            </td>
                        @endif
                    </tr>
                    @empty
                    <tr><td colspan="11" class="text-center p-12 text-gray-400 italic">Belum ada data jadwal.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-6 flex flex-col md:flex-row justify-between items-center">
            <div class="text-sm text-gray-600 mb-4 md:mb-0">Menampilkan {{ $jadwals->firstItem() ?? 0 }} sampai {{ $jadwals->lastItem() ?? 0 }} dari {{ $jadwals->total() }} data</div>
            <div class="flex justify-end">{{ $jadwals->links() }}</div>
        </div>
    </div>
    <div id="deleteModal" class="fixed inset-0 backdrop-blur-sm bg-black/10 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">
            <h2 class="text-lg font-semibold text-gray-800">Hapus Jadwal</h2>
            <p class="text-sm text-gray-500 mt-1">Yakin ingin menghapus <span id="namaJadwal" class="font-bold text-red-600"></span> ?</p>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg bg-gray-200">Batal</button>
                <form id="deleteForm" method="POST"> @csrf @method('DELETE') <button type="submit" class="px-4 py-2 rounded-lg bg-red-500 text-white">Hapus</button></form>
            </div>
        </div>
    </div>
    <script>
        function openModal(id, nama) {
            document.getElementById('deleteModal').classList.replace('hidden', 'flex');
            document.getElementById('namaJadwal').innerText = `"${nama}"`;
            document.getElementById('deleteForm').action = "{{ route('admin.jadwal.destroy', ':id') }}".replace(':id', id);
        }
        function closeModal() { document.getElementById('deleteModal').classList.replace('flex', 'hidden'); }
    </script>
@endsection