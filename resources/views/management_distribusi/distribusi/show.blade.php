@extends('layouts.app')

@section('content')
<div class="mx-3">
    <h2 class="text-xl font-bold">Detail Data Distribusi</h2>
    <p class="text-gray-500 text-sm">
        Informasi distribusi (hanya untuk melihat)
    </p>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">

    <!-- ================= DATA UTAMA ================= -->
    <div class="grid grid-cols-2 gap-6">

        <div>
            <label>Bencana</label>
            <input type="text"
                value="{{ $distribusi->bencana->nama_bencana ?? '-' }}"
                class="w-full border rounded-lg p-3 bg-gray-100" readonly>
        </div>

        <div>
            <label>Posko</label>
            <input type="text"
                value="{{ $distribusi->posko->nama_posko ?? '-' }}"
                class="w-full border rounded-lg p-3 bg-gray-100" readonly>
        </div>

        <div>
            <label>Tanggal</label>
            <input type="date"
                value="{{ $distribusi->tanggal_distribusi }}"
                class="w-full border rounded-lg p-3 bg-gray-100" readonly>
        </div>

        <div>
            <label>Lokasi</label>
            <input type="text"
                value="{{ $distribusi->lokasi_distribusi }}"
                class="w-full border rounded-lg p-3 bg-gray-100" readonly>
        </div>

        <!-- ✅ DESA (SAMA SEPERTI EDIT - SAFE RELATION) -->
        <div>
            <label>Desa Posko</label>
            <input type="text"
                value="{{ optional(optional($distribusi->posko)->desa)->nama_desa ?? '-' }}"
                class="w-full border rounded-lg p-3 bg-gray-100"
                readonly>
        </div>

        <div>
            <label>Kendaraan</label>
            <input type="text"
                value="{{ $distribusi->kendaraan }}"
                class="w-full border rounded-lg p-3 bg-gray-100" readonly>
        </div>

        <div>
            <label>Nama Supir</label>
            <input type="text"
                value="{{ $distribusi->nama_supir }}"
                class="w-full border rounded-lg p-3 bg-gray-100" readonly>
        </div>

        <div>
            <label>No Kendaraan</label>
            <input type="text"
                value="{{ $distribusi->nomor_kendaraan }}"
                class="w-full border rounded-lg p-3 bg-gray-100" readonly>
        </div>

        <div>
            <label>Kategori</label>
            <input type="text"
                value="{{ ucfirst(str_replace('_',' ', $distribusi->kategori_distribusi)) }}"
                class="w-full border rounded-lg p-3 bg-gray-100" readonly>
        </div>

        <!-- ✅ STATUS (DISAMAKAN DENGAN EDIT - SELECT STYLE) -->
        <div>
            <label>Status</label>
            <select class="w-full border rounded-lg p-3 bg-gray-100" disabled>
                <option value="pending" {{ $distribusi->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="dikirim" {{ $distribusi->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                <option value="selesai" {{ $distribusi->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

    </div>

    <!-- KETERANGAN -->
    <div class="mt-4">
        <label>Keterangan</label>
        <textarea
            class="w-full border rounded-lg p-3 bg-gray-100"
            rows="3" readonly>{{ $distribusi->keterangan }}</textarea>
    </div>

    <!-- ================= DETAIL BARANG ================= -->
    <h3 class="font-bold mt-6">Detail Barang</h3>

    <div class="mt-4 overflow-x-auto">

        <table class="w-full border border-gray-300 rounded-lg overflow-hidden text-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-3 text-left">Nama Barang</th>
                    <th class="border p-3 text-center">Jumlah Keluar</th>
                    <th class="border p-3 text-center">Jumlah Kirim</th>
                    <th class="border p-3 text-center">Satuan</th>
                </tr>
            </thead>

            <tbody>
                @forelse($distribusi->detailDistribusis as $detail)
                    <tr class="hover:bg-gray-50">

                        <td class="border p-3">
                            {{ $detail->barangKeluar?->barang?->nama_barang ?? '-' }}
                        </td>

                        <td class="border p-3 text-center">
                            {{ $detail->barangKeluar->jumlah ?? 0 }}
                        </td>

                        <td class="border p-3 text-center">
                            {{ $detail->jumlah_kirim }}
                        </td>

                        <td class="border p-3 text-center">
                            {{ $detail->barangKeluar?->barang?->satuan ?? '-' }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="border p-3 text-center text-gray-500">
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    <!-- BUTTON -->
    <div class="flex justify-end gap-3 mt-6">
        <a href="{{ route('management_distribusi.distribusi.index') }}"
           class="px-4 py-2 bg-gray-300 rounded-lg">
            Kembali
        </a>
    </div>

</div>

@endsection