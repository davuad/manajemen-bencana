@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6 max-w-5xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-6">

        <h2 class="text-2xl font-bold">
            Tambah Data Pengembalian
        </h2>

        <p class="text-gray-500 text-sm">
            Pilih pengambilan untuk menampilkan semua data barang
        </p>

    </div>

    <form action="{{ route('admin.management_barang.pengembalian.store') }}"
          method="POST">

        @csrf

        {{-- PILIH PENGAMBILAN --}}
        <div class="mb-6">

            <label class="font-semibold text-gray-700">
                Pengambilan
            </label>

            <select id="pengambilan"
                    class="w-full border rounded-lg p-3 mt-2">

                <option value="">
                    -- Pilih Pengambilan --
                </option>

                @php
                    $grouped = $pengambilan->groupBy(function($item){
                        return
                            $item->tujuan .
                            '-' .
                            $item->tanggal_pengambilan .
                            '-' .
                            $item->posko_id .
                            '-' .
                            $item->bencana_id;
                    });
                @endphp

                @foreach($grouped as $items)

                    @php
                        $p = $items->first();
                    @endphp

                    <option value="{{ $p->id }}"
                        data-tujuan="{{ $p->tujuan }}"
                        data-petugas="{{ $p->petugas->nama_petugas ?? '-' }}"
                        data-posko="{{ $p->posko->nama_posko ?? '-' }}"
                        data-bencana="{{ $p->bencana->nama_bencana ?? 'Bencana '.$p->bencana_id }}"
                        data-tanggal="{{ $p->tanggal_pengambilan }}">

                        {{ $p->bencana->nama_bencana ?? 'Bencana '.$p->bencana_id }}
                        |
                        {{ $p->tujuan }}
                        |
                        {{ $p->tanggal_pengambilan }}

                    </option>

                @endforeach

            </select>

        </div>

        {{-- INFO --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

            {{-- BENCANA --}}
            <div>

                <label class="font-semibold text-gray-700">
                    Bencana
                </label>

                <div id="bencana"
                     class="mt-1 text-gray-600">

                    -

                </div>

            </div>

            {{-- TUJUAN --}}
            <div>

                <label class="font-semibold text-gray-700">
                    Tujuan
                </label>

                <div id="tujuan"
                     class="mt-1 text-gray-600">

                    -

                </div>

            </div>

            {{-- PETUGAS --}}
            <div>

                <label class="font-semibold text-gray-700">
                    Petugas
                </label>

                <div id="petugas"
                     class="mt-1 text-gray-600">

                    -

                </div>

            </div>

            {{-- POSKO --}}
            <div>

                <label class="font-semibold text-gray-700">
                    Posko
                </label>

                <div id="posko"
                     class="mt-1 text-gray-600">

                    -

                </div>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="mb-6">

            <h3 class="text-lg font-semibold mb-4">
                Data Barang Pengembalian
            </h3>

            <div class="overflow-x-auto">

                <table class="w-full border text-sm">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="p-3 text-center">
                                No
                            </th>

                            <th class="p-3 text-left">
                                Nama Barang
                            </th>

                            <th class="p-3 text-center">
                                Satuan
                            </th>

                            <th class="p-3 text-center">
                                Jumlah Diambil
                            </th>

                            <th class="p-3 text-center">
                                Jumlah Kembali
                            </th>

                        </tr>

                    </thead>

                    <tbody id="gridBarang">

                        <tr>

                            <td colspan="5"
                                class="text-center p-4 text-gray-500">

                                Silakan pilih pengambilan terlebih dahulu

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

        {{-- FORM --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- TANGGAL --}}
            <div>

                <label class="font-semibold text-gray-700">
                    Tanggal Pengembalian
                </label>

                <input type="date"
                       name="tanggal_pengembalian"
                       class="w-full border rounded-lg p-3 mt-2"
                       required>

            </div>

            {{-- STATUS --}}
            <div>

                <label class="font-semibold text-gray-700">
                    Status
                </label>

                <select name="status"
                        class="w-full border rounded-lg p-3 mt-2"
                        required>

                    <option value="Ditangani">
                        Ditangani
                    </option>

                    <option value="Selesai">
                        Selesai
                    </option>

                    <option value="Dibatalkan">
                        Dibatalkan
                    </option>

                </select>

            </div>

            {{-- KETERANGAN --}}
            <div class="col-span-1 md:col-span-2">

                <label class="font-semibold text-gray-700">
                    Keterangan
                </label>

                <textarea name="keterangan"
                        rows="4"
                        class="w-full border rounded-lg p-3 mt-2"
                        placeholder="Masukkan keterangan"></textarea>

            </div>

        </div>

        {{-- BUTTON --}}
        <div class="mt-8 flex justify-between">

            <a href="{{ route('admin.management_barang.pengembalian.index') }}"
               class="px-5 py-2 bg-gray-300 rounded-lg">

                Kembali

            </a>

            <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg">

                Simpan Data

            </button>

        </div>

    </form>

</div>

{{-- SCRIPT --}}
<script>
document.getElementById('pengambilan').addEventListener('change', function () {
    let option = this.options[this.selectedIndex];
    let id = this.value;

    // 1. UPDATE INFO HEADER ATAS
    document.getElementById('bencana').innerText = option.dataset.bencana || '-';
    document.getElementById('tujuan').innerText = option.dataset.tujuan || '-';
    document.getElementById('petugas').innerText = option.dataset.petugas || '-';
    document.getElementById('posko').innerText = option.dataset.posko || '-';

    // RESET JIKA PILIHAN KOSONG
    if (!id) {
        document.getElementById('gridBarang').innerHTML = `
            <tr>
                <td colspan="5" class="text-center p-4 text-gray-500">
                    Silakan pilih pengambilan terlebih dahulu
                </td>
            </tr>
        `;
        return;
    }

    // TAMPILKAN LOADING SEMENTARA
    document.getElementById('gridBarang').innerHTML = `
        <tr>
            <td colspan="5" class="text-center p-4 text-blue-500">
                Sedang memuat data barang...
            </td>
        </tr>
    `;

    // 2. FETCH KE ENDPOINT YANG BENAR (Menyesuaikan dengan nama method getPengambilan di Controller)
   fetch("{{ url('admin/management-barang/pengembalian/get-pengambilan') }}/" + id)
.then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        let html = '';

        if (!data || data.length === 0) {
            html = `
                <tr>
                    <td colspan="5" class="text-center p-4 text-red-500">
                        Data barang tidak ditemukan
                    </td>
                </tr>
            `;
            document.getElementById('gridBarang').innerHTML = html;
            return;
        }

        // LOOP DATA GRUP BARANG
        data.forEach((item, index) => {
            html += `
                <tr class="border-t">
                    <td class="p-3 text-center">
                        ${index + 1}
                    </td>
                    <td class="p-3">
                        ${item.barang?.nama_barang ?? '-'}
                        <input type="hidden" name="pengambilan_id[]" value="${item.id}">
                    </td>
                    <td class="p-3 text-center">
                        ${item.barang?.satuan ?? '-'}
                    </td>
                    <td class="p-3 text-center">
                        <span class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 font-semibold">
                            ${item.jumlah_ambil}
                        </span>
                    </td>
                    <td class="p-3 text-center">
                        <input type="number" 
                               name="jumlah_kembali[]" 
                               min="0" 
                               max="${item.jumlah_ambil}" 
                               value="${item.jumlah_ambil}" 
                               class="border rounded-lg p-2 w-24 text-center"
                               required>
                    </td>
                </tr>
            `;
        });

        document.getElementById('gridBarang').innerHTML = html;
    })
    .catch(error => {
        console.error("Error Detail:", error);
        document.getElementById('gridBarang').innerHTML = `
            <tr>
                <td colspan="5" class="text-center p-4 text-red-500">
                    Terjadi kesalahan mengambil data (Gagal memuat API)
                </td>
            </tr>
        `;
    });
});
</script>
@endsection