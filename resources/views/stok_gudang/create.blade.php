@extends('layouts.app')

@section('content')

<div class="mx-3">
    <h2 class="text-xl font-bold">Tambah Stok Gudang</h2>
    <p class="text-gray-500 text-sm">
        Tambahkan stok barang ke gudang
    </p>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">

<form action="{{ route('stok_gudang.store') }}" method="POST" class="space-y-6">
@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- GUDANG --}}
    <div>
        <label class="block font-medium text-gray-700">
            Gudang <span class="text-red-500">*</span>
        </label>

        <select name="gudang_id" class="w-full border rounded-lg p-3">
            <option value="">-- Pilih Gudang --</option>

            @foreach($gudang as $g)
                <option value="{{ $g->id }}" {{ old('gudang_id') == $g->id ? 'selected' : '' }}>
                    {{ $g->nama_gudang }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- BARANG --}}
    <div>
        <label class="block font-medium text-gray-700">
            Barang <span class="text-red-500">*</span>
        </label>

        <select name="barang_id" class="w-full border rounded-lg p-3">
            <option value="">-- Pilih Barang --</option>

            @foreach($barang as $b)
                <option value="{{ $b->id_barang }}" {{ old('barang_id') == $b->id_barang ? 'selected' : '' }}>
                    {{ $b->nama_barang }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- JUMLAH --}}
    <div>
        <label class="block font-medium text-gray-700">
            Jumlah Stok <span class="text-red-500">*</span>
        </label>

        <input type="number" name="jumlah_stok"
            value="{{ old('jumlah_stok') }}"
            class="w-full border rounded-lg p-3">
    </div>

    {{-- KONDISI --}}
    <div>
        <label class="block font-medium text-gray-700">
            Kondisi Barang <span class="text-red-500">*</span>
        </label>

        <select name="kondisi_barang" class="w-full border rounded-lg p-3">
            <option value="baik" {{ old('kondisi_barang') == 'baik' ? 'selected' : '' }}>Baik</option>
            <option value="rusak" {{ old('kondisi_barang') == 'rusak' ? 'selected' : '' }}>Rusak</option>
        </select>
    </div>

    {{-- KETERANGAN --}}
    <div class="md:col-span-2">
        <label class="block font-medium text-gray-700">
            Keterangan
        </label>

        <input type="text" name="keterangan"
            value="{{ old('keterangan') }}"
            class="w-full border rounded-lg p-3">
    </div>

</div>

<div class="flex justify-end gap-3">
    <a href="{{ route('stok_gudang.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg">
        Batal
    </a>

    <button class="px-6 py-2 bg-blue-600 text-white rounded-lg">
        Simpan
    </button>
</div>

</form>

</div>

@endsection