@extends('layouts.app')

@section('content')

<div class="mx-3">
    <h2 class="text-xl font-bold">Edit Data Distribusi</h2>
    <p class="text-gray-500 text-sm">
        Ubah data distribusi bantuan
    </p>
</div>

<div class="bg-white rounded-xl p-6 m-3 mt-5 shadow">

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <b>Terjadi kesalahan:</b>
            <ul class="list-disc ml-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.management_distribusi.distribusi.update', $distribusi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- ================= DATA UTAMA ================= -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label>Bencana *</label>
                <select name="bencana_id" class="w-full border rounded-lg p-3" required>
                    @foreach($bencana as $b)
                        <option value="{{ $b->id }}"
                            {{ $distribusi->bencana_id == $b->id ? 'selected' : '' }}>
                            {{ $b->nama_bencana }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Posko *</label>
                <select name="posko_id"
                        class="w-full border rounded-lg p-3"
                        required
                        onchange="autoPosko(this)">

                    @foreach($posko as $p)
                        <option value="{{ $p->id }}"
                            data-lokasi="{{ $p->lokasi }}"
                            data-desa="{{ optional($p->desa)->nama_desa }}"
                            {{ $distribusi->posko_id == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_posko }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div>
                <label>Tanggal Distribusi *</label>
                <input type="date"
                       name="tanggal_distribusi"
                       value="{{ $distribusi->tanggal_distribusi }}"
                       class="w-full border rounded-lg p-3"
                       required>
            </div>

            <div>
                <label>Lokasi Distribusi *</label>
                <input type="text"
                       name="lokasi_distribusi"
                       value="{{ $distribusi->lokasi_distribusi }}"
                       class="w-full border rounded-lg p-3 bg-gray-100"
                       readonly
                       required>
            </div>

            <div>
                <label>Desa Posko</label>
                <input type="text"
                       id="nama_desa"
                       value="{{ optional(optional($distribusi->posko)->desa)->nama_desa }}"
                       class="w-full border rounded-lg p-3 bg-gray-100"
                       readonly>
            </div>

            <div>
                <label>Kendaraan *</label>
                <input type="text"
                       name="kendaraan"
                       value="{{ $distribusi->kendaraan }}"
                       class="w-full border rounded-lg p-3"
                       required>
            </div>

            <div>
                <label>Nama Supir *</label>
                <input type="text"
                       name="nama_supir"
                       value="{{ $distribusi->nama_supir }}"
                       class="w-full border rounded-lg p-3"
                       required>
            </div>

            <div>
                <label>Nomor Kendaraan *</label>
                <input type="text"
                       name="nomor_kendaraan"
                       value="{{ $distribusi->nomor_kendaraan }}"
                       class="w-full border rounded-lg p-3"
                       required>
            </div>

            <div>
                <label>Kategori Distribusi *</label>
                <select name="kategori_distribusi"
                        class="w-full border rounded-lg p-3"
                        required>

                    <option value="bencana"
                        {{ $distribusi->kategori_distribusi == 'bencana' ? 'selected' : '' }}>
                        Bencana
                    </option>

                    <option value="pasca_bencana"
                        {{ $distribusi->kategori_distribusi == 'pasca_bencana' ? 'selected' : '' }}>
                        Pasca Bencana
                    </option>

                </select>
            </div>

            <div>
                <label>Status *</label>
                <select name="status"
                        class="w-full border rounded-lg p-3"
                        required>

                    <option value="pending"
                        {{ $distribusi->status == 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="dikirim"
                        {{ $distribusi->status == 'dikirim' ? 'selected' : '' }}>
                        Dikirim
                    </option>

                    <option value="selesai"
                        {{ $distribusi->status == 'selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>

                </select>
            </div>

            <div class="md:col-span-2">
                <label>Keterangan</label>

                <textarea name="keterangan"
                          rows="3"
                          class="w-full border rounded-lg p-3">{{ $distribusi->keterangan }}</textarea>
            </div>

        </div>

        <!-- ================= DETAIL BARANG ================= -->
        <h3 class="font-bold mt-8 mb-3">Detail Barang</h3>

        <div class="overflow-x-auto">

            <table class="w-full border border-gray-300 rounded-lg text-sm">

                <thead class="bg-gray-200">
                    <tr>
                        <th class="border p-3 text-left">Nama Barang</th>
                        <th class="border p-3 text-center">Jumlah Keluar</th>
                        <th class="border p-3 text-center">Jumlah Kirim</th>
                        <th class="border p-3 text-center">Satuan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($barangKeluar as $i => $bk)

@php
    $detail = $distribusi->detailDistribusis
        ->where('detail_barang_keluar_id', $bk->id)
        ->first();
@endphp

<tr>

    <!-- NAMA BARANG -->
    <td class="border p-3">
        {{ optional($bk->barang)->nama_barang ?? '-' }}

        <input
            type="hidden"
            name="barang_detail[{{ $i }}][detail_barang_keluar_id]"
            value="{{ $bk->id }}">
    </td>

    <!-- JUMLAH KELUAR -->
    <td class="border p-3 text-center">
        <input
            type="number"
            value="{{ $bk->jumlah_keluar }}"
            class="w-24 border rounded-lg p-2 text-center bg-gray-100"
            readonly>
    </td>

    <!-- JUMLAH KIRIM -->
    <td class="border p-3 text-center">
        <input
            type="number"
            name="barang_detail[{{ $i }}][jumlah_kirim]"
            value="{{ $detail->jumlah_kirim ?? $bk->jumlah_keluar }}"
            min="1"
            max="{{ $bk->jumlah_keluar }}"
            data-max="{{ $bk->jumlah_keluar }}"
            class="w-24 border rounded-lg p-2 text-center"
            oninput="validasiEdit(this)"
            required>
    </td>

    <!-- SATUAN -->
    <td class="border p-3 text-center">

        <input
            type="text"
            value="{{ optional($bk->barang)->satuan ?? '-' }}"
            class="w-28 border rounded-lg p-2 text-center bg-gray-100"
            readonly>

        <input
            type="hidden"
            name="barang_detail[{{ $i }}][satuan]"
            value="{{ optional($bk->barang)->satuan }}">

    </td>

</tr>

@empty

<tr>
    <td colspan="4" class="text-center text-gray-500 p-4">
        Tidak ada data barang keluar
    </td>
</tr>

@endforelse

</tbody>

</table>

</div>

<!-- BUTTON -->
<div class="flex justify-end gap-3 mt-6">

    <a href="{{ route('admin.management_distribusi.distribusi.index') }}"
       class="px-4 py-2 bg-gray-300 rounded-lg">
        Batal
    </a>

    <button
        type="submit"
        class="px-6 py-2 bg-blue-600 text-white rounded-lg">
        Update Data
    </button>

</div>

</form>

</div>

<script>

function autoPosko(select){

    let option = select.options[select.selectedIndex];

    document.querySelector('input[name="lokasi_distribusi"]').value =
        option.getAttribute('data-lokasi') ?? '';

    document.getElementById('nama_desa').value =
        option.getAttribute('data-desa') ?? '';

}

function validasiEdit(input){

    let max = parseInt(input.dataset.max);
    let value = parseInt(input.value);

    if(isNaN(value) || value < 1){
        input.value = 1;
        return;
    }

    if(value > max){
        alert('Jumlah kirim tidak boleh melebihi jumlah keluar ('+max+')');
        input.value = max;
    }

}

window.onload = function(){

    let select = document.querySelector('select[name="posko_id"]');

    if(select){
        autoPosko(select);
    }

}

</script>

@endsection