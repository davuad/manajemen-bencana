@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold">Data Barang</h2>
            <p class="text-gray-500 text-sm">
                Kelola data barang logistik
            </p>
        </div>

        <a href="{{ route('admin.barang.create') }}"
            class="bg-indigo-700 text-white px-4 py-2 rounded-lg">
            + Tambah Barang
        </a>
    </div>

    <!-- SEARCH -->
<form method="GET" action="{{ route('admin.barang.index') }}" class="mb-6">

    <div class="flex flex-wrap items-center gap-3">

        <input
            type="text"
            name="search"
            value="{{ $search ?? '' }}"
            placeholder="Cari ID atau nama barang..."
            class="w-80 border rounded-lg p-3">

        <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg">
            🔍 Search
        </button>

        <a href="{{ route('admin.barang.index') }}"
            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg">
            Reset
        </a>

    </div>

</form>

    <!-- TABLE -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-center">No</th>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Jenis Barang</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Keterangan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($data as $index => $item)
                <tr class="border-t">
                    <td class="p-2 text-center">{{ $index + 1 }}</td>
                    <td class="p-2">{{ $item->id_barang }}</td>
                    <td class="p-2">{{ $item->nama_barang }}</td>

                    <!-- RELASI -->
                    <td class="p-2">
                        {{ $item->jenis->nama_jenis_barang ?? '-' }}
                    </td>

                    <td class="p-2">{{ $item->stok }}</td>
                    <td class="p-2">{{ $item->satuan }}</td>
                    <td class="p-2">{{ $item->keterangan }}</td>

                    <td class="p-2 text-center">

                        <!-- EDIT -->
                        <a href="{{ route('admin.barang.edit', $item->id_barang) }}"
                            class="text-blue-500 mr-2">
                            ✏️
                        </a>

                        <!-- DELETE -->
                        <form action="{{ route('admin.barang.destroy', $item->id_barang) }}"
                                method="POST"
                                class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-500">
                                🗑️
                            </button>
                        </form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center p-4">
                        Data belum ada
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection