@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Tambah Kategori Bencana</h2>
        <p class="text-gray-500 text-sm">
            Masukkan data kategori bencana baru
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">
        <form action="{{ route('admin.kategori_bencana.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nama --}}
                <div>
                    <label class="block font-medium text-gray-700">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_kategori" class="w-full border rounded-lg p-3"
                        placeholder="Contoh: Banjir">
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block font-medium text-gray-700">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="deskripsi" class="w-full border rounded-lg p-3"
                        placeholder="Contoh: Luapan air akibat hujan tinggi">
                </div>

            </div>

            {{-- Button --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.kategori_bencana.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </a>

                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                    Simpan
                </button>
            </div>

        </form>
    </div>
@endsection
