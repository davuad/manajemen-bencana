@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Edit Stok Gudang</h2>
        <p class="text-gray-500 text-sm">
            Perbarui data stok gudang
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">

        <form action="{{ route('admin.stok_gudang.update', $stok->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- GUDANG --}}
                <div>
                    <label class="block font-medium text-gray-700">
                        Gudang <span class="text-red-500">*</span>
                    </label>

                    <select name="gudang_id" class="w-full border rounded-lg p-3">
                        @foreach ($gudang as $g)
                            <option value="{{ $g->id }}"
                                {{ old('gudang_id', $stok->gudang_id) == $g->id ? 'selected' : '' }}>
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
                        @foreach ($barang as $b)
                            <option value="{{ $b->id_barang }}"
                                {{ old('barang_id', $stok->barang_id) == $b->id_barang ? 'selected' : '' }}>
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

                    <input type="number" name="jumlah_stok" value="{{ old('jumlah_stok', $stok->jumlah_stok) }}"
                        class="w-full border rounded-lg p-3">
                </div>

                {{-- KONDISI --}}
                <div>
                    <label class="block font-medium text-gray-700">
                        Kondisi Barang <span class="text-red-500">*</span>
                    </label>

                    <select name="kondisi_barang" class="w-full border rounded-lg p-3">
                        <option value="baik"
                            {{ old('kondisi_barang', $stok->kondisi_barang) == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak"
                            {{ old('kondisi_barang', $stok->kondisi_barang) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>

                {{-- KETERANGAN --}}
                <div class="md:col-span-2">
                    <label class="block font-medium text-gray-700">
                        Keterangan
                    </label>

                    <input type="text" name="keterangan" value="{{ old('keterangan', $stok->keterangan) }}"
                        class="w-full border rounded-lg p-3">
                </div>

            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.stok_gudang.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </a>

                <button class="px-6 py-2 bg-yellow-500 text-white rounded-lg">
                    Update
                </button>
            </div>

        </form>

    </div>
@endsection
