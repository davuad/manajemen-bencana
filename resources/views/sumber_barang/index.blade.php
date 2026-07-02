@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold">Data Sumber Barang</h2>
            <p class="text-gray-500 text-sm">
                Kelola data sumber barang bantuan
            </p>
        </div>

        <a href="{{ route('admin.sumber-barang.create') }}"
           class="bg-indigo-700 text-white px-4 py-2 rounded-lg">
            + Tambah Sumber Barang
        </a>
    </div>

   <!-- SEARCH -->
<form method="GET" action="{{ route('admin.sumber-barang.index') }}" class="mb-6">

    <div class="flex flex-wrap items-center gap-3">

        <input
            type="text"
            name="search"
            value="{{ $search ?? '' }}"
            placeholder="Cari nama sumber barang..."
            class="w-80 border rounded-lg p-3">

        <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg">
            🔍 Search
        </button>

        <a href="{{ route('admin.sumber-barang.index') }}"
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
                    <th class="text-left">ID Sumber</th>
                    <th class="text-left">Nama Sumber</th>
                    <th class="text-left">Keterangan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($data as $index => $item)
                <tr class="border-t">
                    <td class="p-2 text-center">{{ $index + 1 }}</td>
                    <td class="p-2">{{ $item->id_sumber }}</td>
                    <td class="p-2">{{ $item->nama_sumber }}</td>
                    <td class="p-2">{{ $item->keterangan }}</td>
                    <td class="p-2 text-center">

                        <!-- EDIT -->
                        <a href="{{ route('admin.sumber-barang.edit', $item->id_sumber) }}"
                            class="text-blue-500 mr-2">
                            ✏️
                        </a>

                        <!-- DELETE -->
                        <form action="{{ route('admin.sumber-barang.destroy', $item->id_sumber) }}"
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
                    <td colspan="5" class="text-center p-4">
                        Data belum ada
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection