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

    <!-- ALERT -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- SEARCH -->
    <div class="mb-6">
        <form method="GET">
            <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama sumber barang..."
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
        </form>
    </div>

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