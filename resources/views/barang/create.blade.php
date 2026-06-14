@extends('layouts.app')

@section('content')

<div class="mx-3">
    <h2 class="text-xl font-bold">Tambah Barang</h2>
    <p class="text-gray-500 text-sm">
        Tambahkan data barang logistik
    </p>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form action="/barang" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Nama Barang -->
            <div class="md:col-span-2">
                <label class="block font-medium">Nama Barang *</label>
                <input type="text"
                       name="nama_barang"
                       class="w-full border rounded-lg p-3"
                       placeholder="Contoh: Beras, Obat, Selimut">
            </div>

            <!-- Jenis Barang (RELASI) -->
            <div>
                <label class="block font-medium">Jenis Barang *</label>
                <select name="id_jenis_barang"
                        class="w-full border rounded-lg p-3">
                    <option value="">Pilih Jenis Barang</option>
                    @foreach($jenis as $j)
                        <option value="{{ $j->id_jenis_barang }}">
                            {{ $j->nama_jenis_barang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Stok -->
            <div>
                <label class="block font-medium">Stok *</label>
                <input type="number"
                       name="stok"
                       class="w-full border rounded-lg p-3"
                       placeholder="Contoh: 100">
            </div>

            <!-- Satuan -->
            <div>
                <label class="block font-medium">Satuan *</label>
                <input type="text"
                       name="satuan"
                       class="w-full border rounded-lg p-3"
                       placeholder="Contoh: Kg, Box, Pcs">
            </div>

            <!-- Keterangan -->
            <div class="md:col-span-2">
                <label class="block font-medium">Keterangan</label>
                <textarea name="keterangan"
                          class="w-full border rounded-lg p-3"
                          rows="3"
                          placeholder="Tambahkan deskripsi barang..."></textarea>
            </div>

        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-3">
            <a href="/barang"
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