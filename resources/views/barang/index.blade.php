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

    <!-- ALERT -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- SEARCH -->
    <form method="GET" action="{{ route('admin.barang.index') }}" class="mb-4">
        <input type="text"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Cari ID atau nama barang..."
                class="w-full border rounded-lg p-3">
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