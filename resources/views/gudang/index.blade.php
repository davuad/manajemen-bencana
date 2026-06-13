@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold">DATA GUDANG</h2>
                <p class="text-gray-500 text-sm">Data gudang penyimpanan</p>
            </div>

            <a href="{{ route('admin.gudang.create') }}" class="bg-indigo-700 text-white px-4 py-2 rounded-lg">
                + Tambah Gudang
            </a>
        </div>

        {{-- SEARCH --}}
        <form method="GET">
            <div class="flex gap-4 mb-6">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari gudang..."
                    class="flex-1 border rounded-lg px-4 py-2">

                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
                    Cari
                </button>
            </div>
        </form>

        {{-- TABLE --}}
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-center">No</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Alamat</th>
                    <th class="text-center">Kapasitas</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($gudang as $key => $g)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3 text-center">
                            {{ $gudang->firstItem() + $key }}
                        </td>
                        <td class="text-center">{{ $g->nama_gudang }}</td>
                        <td class="text-center">{{ $g->alamat }}</td>
                        <td class="text-center">{{ $g->kapasitas }}</td>
                        <td class="text-center">{{ $g->keterangan }}</td>

                        <td class="flex gap-2 py-3">
                            <a href="{{ route('admin.gudang.edit', $g->id) }}" class="text-blue-500">✏️</a>

                            <form action="{{ route('admin.gudang.destroy', $g->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-4">Data kosong</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $gudang->withQueryString()->links() }}
        </div>

    </div>
@endsection
