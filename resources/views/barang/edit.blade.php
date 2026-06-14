@extends('layouts.app')

@section('content')

<div class="mx-3">
    <h2 class="text-xl font-bold">Edit Barang</h2>
    <p class="text-gray-500 text-sm">
        Perbarui data barang logistik
    </p>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">

    <form action="/barang/{{ $data->id_barang }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- ID -->
            <div>
                <label class="block font-medium">ID Barang</label>
                <input type="text"
                       value="{{ $data->id_barang }}"
                       class="w-full border rounded-lg p-3 bg-gray-100"
                       readonly>
            </div>

            <!-- Nama Barang -->
            <div>
                <label class="block font-medium">Nama Barang *</label>
                <input type="text"
                       name="nama_barang"
                       value="{{ $data->nama_barang }}"
                       class="w-full border rounded-lg p-3"
                       placeholder="Contoh: Beras, Obat, Selimut">
            </div>

            <!-- Jenis Barang -->
            <div>
                <label class="block font-medium">Jenis Barang *</label>
                <select name="id_jenis_barang"
                        class="w-full border rounded-lg p-3">
                    <option value="">Pilih Jenis Barang</option>
                    @foreach($jenis as $j)
                        <option value="{{ $j->id_jenis_barang }}"
                            {{ $data->id_jenis_barang == $j->id_jenis_barang ? 'selected' : '' }}>
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
                       value="{{ $data->stok }}"
                       class="w-full border rounded-lg p-3">
            </div>

            <!-- Satuan -->
            <div>
                <label class="block font-medium">Satuan *</label>
                <input type="text"
                       name="satuan"
                       value="{{ $data->satuan }}"
                       class="w-full border rounded-lg p-3"
                       placeholder="Contoh: Kg, Box, Pcs">
            </div>

            <!-- Keterangan -->
            <div class="md:col-span-2">
                <label class="block font-medium">Keterangan</label>
                <textarea name="keterangan"
                          class="w-full border rounded-lg p-3"
                          rows="3">{{ $data->keterangan }}</textarea>
            </div>

        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-3">
            <a href="/barang"
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