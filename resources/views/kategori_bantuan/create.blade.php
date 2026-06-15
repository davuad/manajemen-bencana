@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Tambah Kategori Bantuan</h2>
        <p class="text-gray-500 text-sm">
            Masukkan kategori bantuan berdasarkan sumber
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">

        <form action="{{ route('admin.kategori_bantuan.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- SUMBER --}}
                <div>
                    <label class="block font-medium text-gray-700">
                        Sumber <span class="text-red-500">*</span>
                    </label>

                    <select name="id_sumber" class="w-full border rounded-lg p-3">
                        <option value="">-- Pilih Sumber --</option>

                        @foreach ($sumber as $s)
                            <option value="{{ $s->id }}">
                                {{ $s->nama_sumber }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- NAMA --}}
                <div>
                    <label class="block font-medium text-gray-700">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>

                    <input type="text" name="nama_kategori" class="w-full border rounded-lg p-3"
                        placeholder="Contoh: Makanan">
                </div>

                {{-- KETERANGAN --}}
                <div class="md:col-span-2">
                    <label class="block font-medium text-gray-700">
                        Keterangan
                    </label>

                    <input type="text" name="keterangan" class="w-full border rounded-lg p-3">
                </div>

            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.kategori_bantuan.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </a>

                <button class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                    Simpan
                </button>
            </div>

        </form>

    </div>
@endsection
