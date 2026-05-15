@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">

        <div>
            <h2 class="text-xl font-bold">KATEGORI BENCANA</h2>
            <p class="text-gray-500 text-sm">
                Data jenis bencana
            </p>
        </div>

        <a href="{{ route('kategori_bencana.create') }}" 
           class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block">
            + Tambah Kategori
        </a>

    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('kategori_bencana.index') }}">
        <div class="flex gap-4 mb-6">

            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari berdasarkan kategori bencana"
                class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
                Cari
            </button>

        </div>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-center">No</th>
                    <th class="text-center">Nama Kategori</th>
                    <th class="text-center">Deskripsi</th>
                    <th class="text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($kategori as $key => $k)
                <tr class="border-t">
                    <td class="p-2 text-center">{{ $key + 1 }}</td>
                    <td class="p-2 text-center">{{ $k->nama_kategori }}</td>
                    <td class="p-2 text-center">{{ $k->deskripsi }}</td>

                    <td class="flex gap-2 py-3">

                        {{-- Edit --}}
                        <a href="{{ route('kategori_bencana.edit', $k->id) }}"
                           class="text-blue-500">
                            ✏️
                        </a>

                        {{-- Delete --}}
                        <form action="{{ route('kategori_bencana.destroy', $k->id) }}" method="POST"
                              onsubmit="return confirm('Yakin hapus data?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="text-red-500">
                                🗑️
                            </button>
                        </form>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center p-4">
                        Data kategori belum ada
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>

@endsection
