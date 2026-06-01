@extends('layouts.app')

@section('content')
<div class="p-6 max-w-xl mx-auto">

    <h1 class="text-2xl font-bold mb-4">Tambah Stok Posko</h1>

    {{-- Error validasi --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('manajemen_barang.stok_posko.store') }}" method="POST">
        @csrf

        {{-- Posko --}}
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama Posko</label>
            <select name="posko_id" class="w-full border rounded px-3 py-2">
                <option value="">-- Pilih Posko --</option>
                @foreach ($posko as $p)
                    <option value="{{ $p->id }}" {{ old('posko_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_posko }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Barang --}}
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama Barang</label>
            <select name="barang_id" class="w-full border rounded px-3 py-2">
                <option value="">-- Pilih Barang --</option>
                @foreach ($barang as $b)
                    <option value="{{ $b->id }}" {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                        {{ $b->nama_barang }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Kategori Distribusi</label>
            <select name="kategori_distribusi" class="w-full border rounded px-3 py-2">
                <option value="">-- Pilih Kategori --</option>
                <option value="bencana" {{ old('kategori_distribusi') == 'bencana' ? 'selected' : '' }}>
                    Bencana
                </option>
                <option value="pasca_bencana" {{ old('kategori_distribusi') == 'pasca_bencana' ? 'selected' : '' }}>
                    Pasca Bencana
                </option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Jumlah Barang</label>
            <input type="number" name="jumlah_barang"
                   value="{{ old('jumlah_barang') }}"
                   class="w-full border rounded px-3 py-2"
                   placeholder="Masukkan jumlah">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Satuan</label>
            <input type="text" name="satuan"
                   value="{{ old('satuan') }}"
                   class="w-full border rounded px-3 py-2"
                   placeholder="pcs / dus / kg">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk"
                   value="{{ old('tanggal_masuk') }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Keterangan</label>
            <textarea name="keterangan"
                      class="w-full border rounded px-3 py-2"
                      placeholder="Opsional">{{ old('keterangan') }}</textarea>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-2">
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Simpan
            </button>

            <a href="{{ route('manajemen_barang.stok_posko.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Kembali
            </a>
        </div>

    </form>
</div>
@endsection