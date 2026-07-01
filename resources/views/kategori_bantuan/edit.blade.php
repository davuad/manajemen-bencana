@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Edit Kategori Bantuan</h2>
        <p class="text-gray-500 text-sm">
            Perbarui kategori bantuan
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">

        <form action="{{ route('admin.kategori_bantuan.update', $kategori->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- SUMBER --}}
                <div>
                    <label class="block font-medium text-gray-700">
                        Sumber <span class="text-red-500">*</span>
                    </label>

                    <select name="id_sumber" class="w-full border rounded-lg p-3">

                        @foreach ($sumber as $s)
                            <option value="{{ $s->id_sumber }}" {{ $kategori->id_sumber == $s->id_sumber ? 'selected' : '' }}>
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

                    <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                        class="w-full border rounded-lg p-3">
                </div>

                {{-- KETERANGAN --}}
                <div class="md:col-span-2">
                    <label class="block font-medium text-gray-700">
                        Keterangan
                    </label>

                   <textarea
                    name="keterangan"
                    rows="4"
                    class="w-full border rounded-lg p-3">{{ old('keterangan', $kategori->keterangan) }}</textarea>
                </div>

            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.kategori_bantuan.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </a>

                <button class="px-6 py-2 bg-yellow-500 text-white rounded-lg">
                    Update
                </button>
            </div>

        </form>

    </div>
@endsection
