{{-- resources/views/distribusi_bantuan/pengajuan_barang/index.blade.php --}}

@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Olah Data Pengajuan Barang</h2>
            <p class="text-gray-500 text-sm">
                Kelola permintaan logistik dan bantuan untuk lokasi bencana
            </p>
        </div>

        <div class="flex gap-2">
            {{-- Tombol Cetak Laporan (Export PDF/Print) --}}
            <button onclick="exportData()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2 shadow-sm">
                <x-heroicon-o-printer class="w-4 h-4"/>
                Cetak Laporan
            </button>

            <a href="{{ route('distribusi_bantuan.pengajuan.create') }}"
               class="bg-indigo-700 hover:bg-indigo-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
                + Tambah Pengajuan
            </a>
        </div>
    </div>

    {{-- Form Filter Dinamis --}}
    <form method="GET" id="filterForm" action="{{ route('distribusi_bantuan.pengajuan.index') }}" class="mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            
            {{-- Search Nama Bencana atau Lokasi --}}
            <div class="lg:col-span-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cari Kejadian/Desa</label>
                <input type="text" name="nama_bencana" value="{{ request('nama_bencana') }}" placeholder="Contoh: Banjir..." class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
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

            {{-- Filter Bulan --}}
            <div>
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

            {{-- Filter Status --}}
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Status</label>
                <select name="status" class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            {{-- Tombol Aksi Filter --}}
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg text-sm font-bold transition shadow-sm">
                    Filter
                </button>
                @if(request()->anyFilled(['nama_bencana', 'tahun', 'bulan', 'status', 'search']))
                    <a href="{{ route('distribusi_bantuan.pengajuan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm transition flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </div>

        {{-- Hidden input untuk trigger export --}}
        <input type="hidden" name="export" id="export_flag" value="">

        {{-- Kolom Search Pegawai (Integrasi ke Form Filter) --}}
        <div class="mt-4 flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Pegawai Pengaju..." class="flex-1 border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-inner bg-white">
            <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-black transition">Cari Pegawai</button>
        </div>
    </form>

<div class="overflow-x-auto">
    <table class="w-full text-sm text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 text-gray-700 uppercase text-[10px] font-bold tracking-wider">
                <th class="p-4 text-center">No</th>
                <th class="p-4 text-center">Tgl Pengajuan</th>
                <th class="p-4 text-left">Identitas Kejadian (Bencana & Lokasi)</th>
                <th class="p-4 text-center">Pegawai Pengaju</th>
                <th class="p-4 text-center">Status</th>
                <th class="p-4 text-left">Aksi</th> {{-- Dibuat text-left agar sejajar --}}
            </tr>
        </thead>

        <tbody class="text-gray-600">
            @forelse($data as $key => $item)
            <tr class="border-t hover:bg-gray-50 transition">
                <td class="p-4 text-center font-medium">{{ $data->firstItem() + $key }}</td>
                <td class="p-4 text-center font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($item->tgl_pengajuan)->format('d/m/Y') }}
                </td>
                
                <td class="p-4 text-left">
                    <div class="text-sm font-bold text-indigo-900 uppercase leading-tight">
                        {{ $item->bencana->kategoriBencana->nama_kategori ?? 'Kategori N/A' }}
                    </div>
                    <div class="text-[11px] text-gray-500 mt-1 flex items-center gap-1 font-medium">
                        <x-heroicon-o-map-pin class="w-3 h-3 text-gray-400"/>
                        Desa {{ $item->bencana->desa->nama_desa ?? '-' }}, Kec. {{ $item->bencana->desa->kecamatan ?? '-' }}
                    </div>
                </td>

                <td class="p-4 text-center">
                    <div class="font-semibold text-gray-800">{{ $item->pegawai->nama_pegawai ?? '-' }}</div>
                    <div class="text-[10px] text-gray-400 font-normal italic">Oleh: {{ $item->creator->nama ?? 'Sistem' }}</div>
                </td>

                <td class="p-4 text-center">
                    @php
                        $statusColor = [
                            'pending' => 'bg-yellow-200 text-yellow-800',
                            'disetujui' => 'bg-green-200 text-green-800',
                            'ditolak' => 'bg-red-200 text-red-800'
                        ];
                        $color = $statusColor[$item->status_pengajuan] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <span class="{{ $color }} px-4 py-2 rounded-full text-[10px] font-semibold uppercase tracking-wide">
                        {{ $item->status_pengajuan }}
                    </span>
                </td>

                {{-- KOLOM AKSI: DISESUAIKAN DENGAN MANAJEMEN PENGGUNA --}}
                <td class="p-4">
                    <div class="flex gap-1 items-center">
                        {{-- Tombol Detail --}}
                        <a href="{{ route('distribusi_bantuan.pengajuan.show', $item->id) }}" 
                           class="text-blue-500 hover:text-blue-700 transition" 
                           title="Lihat Detail">
                            <x-heroicon-o-eye class="w-5 h-5" />
                        </a>

                        @if($item->status_pengajuan === 'pending')
                            {{-- Tombol Edit --}}
                            <a href="{{ route('distribusi_bantuan.pengajuan.edit', $item->id) }}" 
                               class="text-yellow-500 hover:text-yellow-700 transition"
                               title="Edit Data">
                                <x-heroicon-o-pencil-square class="w-5 h-5" />
                            </a>

                            {{-- Tombol Hapus --}}
                            <button type="button" 
                                    onclick="openModal('{{ $item->id }}', 'Pengajuan #{{ $item->id }}')" 
                                    class="text-red-500 hover:text-red-700 transition"
                                    title="Hapus Data">
                                <x-heroicon-o-trash class="w-5 h-5" />
                            </button>
                        @else
                            {{-- State Terkunci (Sama seperti Admin di Manajemen User) --}}
                            <div class="flex gap-1 opacity-30">
                                <x-heroicon-o-pencil-square class="w-5 h-5 text-gray-400 cursor-not-allowed" title="Data sudah final" />
                                <x-heroicon-o-trash class="w-5 h-5 text-gray-400 cursor-not-allowed" title="Data sudah final" />
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center p-12 text-gray-400 italic bg-gray-50/50">
                    Belum ada data pengajuan barang yang ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

    {{-- Pagination Dinamis --}}
    <div class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4 text-sm text-gray-500">
        @if(method_exists($data, 'links'))
            <div>
                Menampilkan 
                <span class="font-semibold text-gray-700">{{ $data->firstItem() ?? 0 }}</span> -
                <span class="font-semibold text-gray-700">{{ $data->lastItem() ?? 0 }}</span> dari 
                <span class="font-semibold text-gray-700">{{ $data->total() }}</span> data
            </div>
            <div class="w-full md:w-auto">
                {{ $data->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>

{{-- MODAL HAPUS --}}
<div id="deleteModal" class="fixed inset-0 backdrop-blur-sm bg-black/30 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center gap-4 mb-4 text-red-600">
            <x-heroicon-o-exclamation-triangle class="w-10 h-10"/>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Konfirmasi Hapus</h3>
                <p class="text-sm text-gray-500">Anda yakin ingin menghapus <span id="namaItem" class="font-bold text-gray-700"></span>? Tindakan ini permanen.</p>
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-6">
            <button onclick="closeModal()" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium">Batal</button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-bold shadow-md hover:bg-red-700 transition">Ya, Hapus Data</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Logic Modal
    function openModal(id, nama) {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('namaItem').innerText = nama;
        let url = "{{ route('distribusi_bantuan.pengajuan.destroy', ':id') }}";
        url = url.replace(':id', id);
        document.getElementById('deleteForm').action = url;
    }

    function closeModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Logic Export
    function exportData() {
        const form = document.getElementById('filterForm');
        const exportFlag = document.getElementById('export_flag');
        exportFlag.value = 'print'; 
        form.target = "_blank"; 
        form.submit();
        
        // Kembalikan ke normal agar filter biasa tidak buka tab baru
        setTimeout(() => {
            exportFlag.value = '';
            form.target = "_self";
        }, 100);
    }
</script>

@endsection