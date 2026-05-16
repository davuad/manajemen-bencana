@extends('layouts.app')

@section('content')

<div class="mx-3">
    <h2 class="text-xl font-bold">Edit Kategori Bencana</h2>
    <p class="text-gray-500 text-sm">
        Perbarui data kategori
    </p>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">
    <form action="{{ route('kategori_bencana.update', $kategori->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Nama --}}
            <div>
                <label class="block font-medium text-gray-700">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_kategori"
                    value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                    class="w-full border rounded-lg p-3">
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block font-medium text-gray-700">
                    Deskripsi <span class="text-red-500">*</span>
                </label>
                <input type="text" name="deskripsi"
                    value="{{ old('deskripsi', $kategori->deskripsi) }}"
                    class="w-full border rounded-lg p-3">
            </div>

        </div>

        {{-- Button --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('kategori_bencana.index') }}" 
               class="px-4 py-2 bg-gray-300 rounded-lg">
                Batal
            </a>

            <button type="submit" 
                class="px-6 py-2 bg-yellow-500 text-white rounded-lg">
                Update
            </button>
        </div>

    </form>
</div>

@endsection