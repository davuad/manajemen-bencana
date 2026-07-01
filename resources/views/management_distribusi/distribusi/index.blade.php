@extends('layouts.app')

@section('content')
@php
    $routePrefix = auth()->user()->hasRole('admin') ? 'admin' : (auth()->user()->hasRole('pegawai') ? 'pegawai' : 'admin');
    $bulanList = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
@endphp
<div class="p-6 w-full">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Data Distribusi</h3>
            <p class="text-sm text-gray-500">Kelola data distribusi bantuan</p>
        </div>

        <a href="{{ route($routePrefix . '.management_distribusi.distribusi.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
            + Tambah
        </a>
    </div>

    <!-- SEARCH & FILTER -->
<form method="GET"
      action="{{ route($routePrefix . '.management_distribusi.distribusi.index') }}">

    <div class="flex flex-wrap gap-4 mb-6 items-end">

        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-600 mb-1">Cari</label>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari bencana, posko, desa, kategori..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Bulan</label>
            <select name="bulan"
                    class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Bulan</option>
                @foreach($bulanList as $key => $nama)
                    <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>
                        {{ $nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Tahun</label>
            <select name="tahun"
                    class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Tahun</option>
                @foreach($tahunList as $thn)
                    <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>
                        {{ $thn }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
            Cari
        </button>

        @if(request('search') || request('bulan') || request('tahun'))
            <a href="{{ route($routePrefix . '.management_distribusi.distribusi.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">
                Reset
            </a>
        @endif

    </div>
</form>

    <!-- TABLE -->
    <div class="bg-white shadow-md rounded-xl p-4">
        <div class="overflow-x-auto">

            <table id="tableDistribusi" class="w-full text-sm border">

                <!-- HEADER -->
                <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                    <tr class="text-center">
                        <th class="p-3">No</th>
                        <th class="p-3 text-left">Bencana</th>
                        <th class="p-3 text-left">Posko</th>
                        <th class="p-3 text-left">Desa</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3 text-left">Lokasi Posko</th>
                        <th class="p-3">Kendaraan</th>
                        <th class="p-3">Supir</th>
                        <th class="p-3">No Kendaraan</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3 text-left">Keterangan</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody>
                    @forelse($distribusi as $item)
                    <tr class="border-t hover:bg-gray-50">

                        <!-- NO -->
                        <td class="p-3 text-center font-semibold">
                            {{ $loop->iteration }}
                        </td>

                        <!-- BENCANA -->
                        <td class="p-3">
                            {{ optional($item->bencana)->nama_bencana ?? '-' }}
                        </td>

                        

                        <!-- POSKO -->
                        <td class="p-3">
                            {{ optional($item->posko)->nama_posko ?? '-' }}
                        </td>

                        <!-- DESA -->
                        <td class="p-3">
                            {{ optional($item->posko->desa)->nama_desa ?? '-' }}
                        </td>

                        <!-- TANGGAL -->
                        <td class="p-3 text-center">
                            {{ $item->tanggal_distribusi }}
                        </td>

                        <!-- LOKASI POSKO -->
                        <td class="p-3">
                            {{ $item->lokasi_distribusi }}
                        </td>

                        <!-- KENDARAAN -->
                        <td class="p-3 text-center">
                            {{ $item->kendaraan }}
                        </td>

                        <!-- SUPIR -->
                        <td class="p-3 text-center">
                            {{ $item->nama_supir }}
                        </td>

                        <!-- NO KENDARAAN -->
                        <td class="p-3 text-center">
                            {{ $item->nomor_kendaraan }}
                        </td>

                        <!-- KATEGORI -->
                        <td class="p-3 text-center">
                            <span class="px-3 py-1 text-xs rounded-full font-semibold
                                {{ $item->kategori_distribusi == 'bencana' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ ucfirst(str_replace('_',' ', $item->kategori_distribusi)) }}
                            </span>
                        </td>

                        <!-- KETERANGAN -->
                        <td class="p-3">
                            {{ $item->keterangan ?? '-' }}
                        </td>

                        <!-- STATUS -->
                        <td class="p-3 text-center">
                            <span class="px-3 py-1 text-xs rounded-full font-semibold
                                {{ $item->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $item->status == 'dikirim' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $item->status == 'selesai' ? 'bg-green-100 text-green-700' : '' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>

                        <!-- AKSI -->
                        <td class="p-3">
                            <div class="flex gap-2 justify-center">

                                <a href="{{ route($routePrefix . '.management_distribusi.distribusi.show', $item->id) }}"
                                   class="p-2 bg-blue-100 text-blue-700 rounded">
                                    👁
                                </a>

                                <a href="{{ route($routePrefix . '.management_distribusi.distribusi.edit', $item->id) }}"
                                   class="p-2 bg-yellow-100 text-yellow-700 rounded">
                                    ✏
                                </a>

                                <button onclick="openModal({{ $item->id }})"
                                    class="p-2 bg-red-100 text-red-600 rounded">
                                    🗑
                                </button>

                                <a href="{{ route($routePrefix . '.management_distribusi.berita_acara.cetak', $item->id) }}"
                                target="_blank"
                                class="p-2 bg-green-100 text-green-700 rounded"
                                title="Cetak Berita Acara">
                                    📄
                                </a>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="text-center p-6 text-gray-400">
                            Tidak ada data distribusi
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

            <!-- TOTAL DATA -->
            <div class="mt-4 text-sm text-gray-600">
                Total data: <strong>{{ $totalData }}</strong>
            </div>

        </div>
    </div>

</div>

<!-- MODAL DELETE -->
<div id="deleteModal" class="fixed inset-0 hidden items-center justify-center bg-black/40 z-50">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">

        <h2 class="text-lg font-semibold mb-2">Hapus Data</h2>

        <p class="text-sm text-gray-500 mb-4">
            Yakin ingin menghapus data ini?
        </p>

        <div class="flex justify-end gap-3">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded">
                Batal
            </button>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button class="px-4 py-2 bg-red-500 text-white rounded">
                    Hapus
                </button>
            </form>
        </div>

    </div>
</div>

<!-- SCRIPT -->
<script>
function openModal(id) {
    let prefix = '{{ $routePrefix }}';
    document.getElementById('deleteForm').action =
        "/" + prefix + "/management-distribusi/distribusi/" + id;

    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}
</script>

@endsection