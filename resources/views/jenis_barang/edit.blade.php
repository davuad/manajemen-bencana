@extends('layouts.app')

@section('content')

<div class="mx-3">
    <h2 class="text-xl font-bold">Edit Jenis Barang</h2>
    <p class="text-gray-500 text-sm">
        Perbarui data jenis barang agar tetap akurat
    </p>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">

    <form action="/jenis-barang/{{ $data->id_jenis_barang }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- ID -->
            <div>
                <label class="block font-medium">ID Jenis Barang</label>
                <input type="text"
                       value="{{ $data->id_jenis_barang }}"
                       class="w-full border rounded-lg p-3 bg-gray-100"
                       readonly>
            </div>

            <!-- Nama -->
            <div>
                <label class="block font-medium">Nama Jenis Barang *</label>
                <input type="text"
                       name="nama_jenis_barang"
                       value="{{ $data->nama_jenis_barang }}"
                       class="w-full border rounded-lg p-3"
                       placeholder="Contoh: Makanan, Obat, Pakaian">
            </div>

            <!-- Keterangan -->
            <div class="md:col-span-2">
                <label class="block font-medium">Keterangan</label>
                <textarea name="keterangan"
                          class="w-full border rounded-lg p-3"
                          rows="3"
                          placeholder="Tambahkan deskripsi...">{{ $data->keterangan }}</textarea>
            </div>

        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-3">
            <a href="/jenis-barang"
               class="px-4 py-2 bg-gray-300 rounded-lg">
                Batal
            </a>

            <button type="submit"
                    class="px-6 py-2 bg-yellow-500 text-white rounded-lg">
                Update Data
            </button>
        </div>

    </form>

</div>

@endsection