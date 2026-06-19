{{-- resources/views/distribusi_bantuan/barang_keluar/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    {{-- Header & Tombol Utama --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Olah Data Barang Keluar</h2>
            <p class="text-gray-500 text-sm">
                Monitoring pengiriman barang logistik dari gudang ke lokasi tujuan.
            </p>
        </div>

        <div class="flex gap-2">
            {{-- Tombol Cetak Laporan --}}
            <button onclick="exportData()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2 shadow-sm">
                <x-heroicon-o-printer class="w-4 h-4"/>
                Cetak Laporan
            </button>

            <a href="{{ route('distribusi_bantuan.barang_keluar.create') }}" 
               class="bg-indigo-700 hover:bg-indigo-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
                + Catat Barang Keluar
            </a>
        </div>
    </div>

    {{-- Form Filter Dinamis --}}
    <form method="GET" id="filterForm" action="{{ route('distribusi_bantuan.barang_keluar.index') }}" class="mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            
            {{-- Filter Bencana / Desa --}}
            <div class="lg:col-span-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cari Bencana/Desa</label>
                <input type="text" name="nama_bencana" value="{{ request('nama_bencana') }}" placeholder="Contoh: Banjir..." class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            {{-- Filter Gudang --}}
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Gudang Sumber</label>
                <select name="gudang_id" class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500">
                    <option value="">Semua Gudang</option>
                    @foreach($all_gudang as $g)
                        <option value="{{ $g->id }}" {{ request('gudang_id') == $g->id ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                    @endforeach
                </select>
            </div>

{{-- Filter Periode (Bulan & Tahun) --}}
<div class="flex gap-2">
    <div class="flex-1">
        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Bulan</label>
        <select name="bulan" class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500">
            <option value="">Semua Bulan</option>
            @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
            @endforeach
        </select>
    </div>

<div class="flex-1">
    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tahun</label>
    <select name="tahun" class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500">
        <option value="">Semua Tahun</option>
        {{-- Loop dari tahun sekarang mundur ke belakang --}}
        @php
            $tahunSekarang = date('Y');
            $tahunMulai = 2020; // Bapak bisa ganti ke 2010 atau lainnya sesuai kebutuhan arsip
        @endphp
        @for($i = $tahunSekarang; $i >= $tahunMulai; $i--)
            <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>
                {{ $i }}
            </option>
        @endfor
    </select>
</div>
</div>

            {{-- Filter Status --}}
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Status</label>
                <select name="status" class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            {{-- Tombol Filter --}}
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg text-sm font-bold transition shadow-sm flex items-center justify-center gap-2">
                    <x-heroicon-o-magnifying-glass class="w-4 h-4"/>
                    Filter
                </button>
                @if(request()->anyFilled(['nama_bencana', 'gudang_id', 'bulan', 'status', 'search']))
                    <a href="{{ route('distribusi_bantuan.barang_keluar.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-600 px-3 py-2 rounded-lg text-sm transition flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </div>

        {{-- Hidden flag untuk Export --}}
        <input type="hidden" name="export" id="export_flag" value="">

        {{-- Search PJ/Petugas --}}
        <div class="mt-4 flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan Nama Petugas Gudang (PJ)..." class="flex-1 border-gray-300 rounded-lg text-sm focus:ring-indigo-500 shadow-inner bg-white">
            <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-black transition">Cari Petugas</button>
        </div>
    </form>

    {{-- Tabel Data --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-700 uppercase text-[10px] font-bold tracking-wider">
                    <th class="p-4 text-center">No</th>
                    <th class="p-4 text-center">Tgl Keluar</th>
                    <th class="p-4">Informasi Kejadian & Lokasi</th>
                    <th class="p-4 text-center">Gudang & PJ</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                @forelse($data as $key => $item)
                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="p-4 text-center font-medium">{{ $data->firstItem() + $key }}</td>
                    <td class="p-4 text-center font-semibold text-gray-800">
                        {{ \Carbon\Carbon::parse($item->tgl_keluar)->format('d/m/Y') }}
                        <div class="text-[10px] text-gray-400 uppercase font-mono">Ref: #{{ $item->pengajuan_barang_id }}</div>
                    </td>
                    <td class="p-4 text-left">
                        <div class="text-sm font-bold text-indigo-900 uppercase leading-tight">
                            {{ $item->pengajuanBarang->bencana->kategoriBencana->nama_kategori ?? 'Kategori N/A' }}
                        </div>
                        <div class="text-[11px] text-gray-500 mt-1 flex items-center gap-1 font-medium italic">
                            <x-heroicon-o-map-pin class="w-3 h-3 text-gray-400"/>
                            Desa {{ $item->pengajuanBarang->bencana->desa->nama_desa ?? '-' }}
                        </div>
                    </td>
                    <td class="p-4 text-center">
                        <div class="font-bold text-gray-800 uppercase text-[11px]">{{ $item->gudang->nama_gudang ?? '-' }}</div>
                        <div class="text-[10px] text-indigo-500 font-bold uppercase tracking-tighter">PJ: {{ $item->petugasGudang->nama_pegawai ?? '-' }}</div>
                    </td>
                    <td class="p-4 text-center">
                        @php
                            $statusColor = [
                                'diproses' => 'bg-blue-200 text-blue-800',
                                'dikirim' => 'bg-yellow-200 text-yellow-800',
                                'selesai' => 'bg-green-200 text-green-800',
                                'dibatalkan' => 'bg-red-200 text-red-800',
                            ];
                            $color = $statusColor[$item->status_proses] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="{{ $color }} px-4 py-2 rounded-full text-[10px] font-semibold uppercase tracking-wide">
                            {{ $item->status_proses }}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            {{-- Ikon Aksi (Selaras dengan Manajemen User) --}}
                            <a href="{{ route('distribusi_bantuan.barang_keluar.show', $item->id) }}" 
                               class="text-blue-500 hover:text-blue-700 transition" 
                               title="Verifikasi & Detail">
                                <x-heroicon-o-eye class="w-6 h-6" />
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-12 text-center text-gray-400 italic bg-gray-50/50">
                        Belum ada riwayat pengeluaran barang yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Dinamis --}}
    <div class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4 text-sm text-gray-500">
        <div>
            Menampilkan 
            <span class="font-semibold text-gray-700">{{ $data->firstItem() ?? 0 }}</span> -
            <span class="font-semibold text-gray-700">{{ $data->lastItem() ?? 0 }}</span> dari 
            <span class="font-semibold text-gray-700">{{ $data->total() }}</span> data barang keluar
        </div>
        <div class="pagination-indigo">
            {{ $data->withQueryString()->links() }}
        </div>
    </div>
</div>

<script>
    // Fungsi untuk cetak laporan (buka di tab baru sesuai filter)
    function exportData() {
        const form = document.getElementById('filterForm');
        const exportFlag = document.getElementById('export_flag');
        
        exportFlag.value = 'print'; 
        form.target = "_blank"; 
        form.submit();
        
        // Reset flag agar filter pencarian biasa tidak buka tab baru
        setTimeout(() => {
            exportFlag.value = '';
            form.target = "_self";
        }, 100);
    }
</script>

@endsection