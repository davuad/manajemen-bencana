@extends('layouts.app')

@section('content')

<div class="mx-3">
    <h2 class="text-xl font-bold">Tambah Jenis Barang</h2>
    <p class="text-gray-500 text-sm">
        Tambahkan data jenis barang untuk kebutuhan logistik bantuan
    </p>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form action="/jenis-barang" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Nama Jenis Barang -->
            <div class="md:col-span-2">
                <label class="block font-medium">Nama Jenis Barang *</label>
                <input type="text"
                       name="nama_jenis_barang"
                       class="w-full border rounded-lg p-3"
                       placeholder="Contoh: Makanan, Pakaian, Obat-obatan">
            </div>

            <!-- Keterangan -->
            <div class="md:col-span-2">
                <label class="block font-medium">Keterangan</label>
                <textarea name="keterangan"
                          class="w-full border rounded-lg p-3"
                          rows="3"
                          placeholder="Tambahkan deskripsi jenis barang..."></textarea>
            </div>

        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-3">
            <a href="/jenis-barang"
               class="px-4 py-2 bg-gray-300 rounded-lg">
                Batal
            </a>

            <button type="submit"
                    class="px-6 py-2 bg-indigo-700 text-white rounded-lg">
                Simpan Data
            </button>
        </div>

    </form>

</div>

@endsection