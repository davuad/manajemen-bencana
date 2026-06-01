@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold">Data Barang Masuk Posko</h2>
            <p class="text-gray-500 text-sm">
                Kelola data stok barang di posko
            </p>
        </div>

        <a href="{{ route('manajemen_barang.stok_posko.create') }}"
           class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block">
            + Tambah Stok
        </a>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('manajemen_barang.stok_posko.index') }}">
        <div class="flex gap-4 mb-6">
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari berdasarkan posko"
                class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">

            <button type="submit" 
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
                Cari
            </button>
        </div>
    </form>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-center">No</th>
                    <th class="text-left">Nama Posko</th>
                    <th class="text-left">Nama Barang</th>
                    <th class="text-left">Kategori Distribusi</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-left">Satuan</th>
                    <th class="text-left">Tanggal Masuk</th>
                    <th class="text-left">Keterangan</th>
                    <th class="text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $item)
                <tr class="border-t">

                    <td class="p-3 text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td class="p-3">
                        {{ $item->posko->nama_posko ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $item->barang->nama_barang ?? '-' }}
                    </td>

                    <td class="p-3 capitalize">
                        {{ str_replace('_', ' ', $item->kategori_distribusi) }}
                    </td>

                    <td class="p-3 text-center">
                        {{ $item->jumlah_barang }}
                    </td>

                    <td class="p-3">
                        {{ $item->satuan }}
                    </td>

                    <td class="p-3">
                        {{ $item->tanggal_masuk }}
                    </td>

                    <td class="p-3">
                        {{ $item->keterangan ?? '-' }}
                    </td>

                    {{-- Aksi --}}
                    <td class="p-3 flex gap-2">

                        <a href="{{ route('manajemen_barang.stok_posko.edit', $item->id) }}"
                           class="text-blue-500 hover:text-blue-700">
                            ✏️
                        </a>

                        <form action="{{ route('manajemen_barang.stok_posko.destroy', $item->id) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin hapus data?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="text-red-500 hover:text-red-700">
                                🗑️
                            </button>
                        </form>

                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center p-4">
                        Data belum ada
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>

@endsection