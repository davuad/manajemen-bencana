@extends('layouts.app')

@section('content')

<div class="mx-3">
    <h2 class="text-xl font-bold">Edit Data Gudang</h2>
    <p class="text-gray-500 text-sm">
        Perbarui data gudang dengan informasi terbaru
    </p>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">

<form action="{{ route('gudang.update', $gudang->id) }}" method="POST" class="space-y-6">
@csrf
@method('PUT')

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- NAMA --}}
    <div>
        <label class="block font-medium text-gray-700">
            Nama Gudang <span class="text-red-500">*</span>
        </label>

        <input type="text" name="nama_gudang"
            value="{{ old('nama_gudang', $gudang->nama_gudang) }}"
            class="w-full border rounded-lg p-3">

        @error('nama_gudang')
            <div class="text-red-500 text-sm mt-1">
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- ALAMAT --}}
    <div>
        <label class="block font-medium text-gray-700">
            Alamat <span class="text-red-500">*</span>
        </label>

        <input type="text" name="alamat"
            value="{{ old('alamat', $gudang->alamat) }}"
            class="w-full border rounded-lg p-3">

        @error('alamat')
            <div class="text-red-500 text-sm mt-1">
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- KAPASITAS --}}
    <div>
        <label class="block font-medium text-gray-700">
            Kapasitas <span class="text-red-500">*</span>
        </label>

        <input type="number" name="kapasitas"
            value="{{ old('kapasitas', $gudang->kapasitas) }}"
            class="w-full border rounded-lg p-3">

        @error('kapasitas')
            <div class="text-red-500 text-sm mt-1">
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- KETERANGAN --}}
    <div>
        <label class="block font-medium text-gray-700">
            Keterangan
        </label>

        <input type="text" name="keterangan"
            value="{{ old('keterangan', $gudang->keterangan) }}"
            class="w-full border rounded-lg p-3">
    </div>

</div>

{{-- BUTTON --}}
<div class="flex justify-end gap-3">
    <a href="{{ route('gudang.index') }}" 
       class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
        Batal
    </a>

    <button type="submit" 
        class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
        Update Data
    </button>
</div>

</form>

</div>

@endsection