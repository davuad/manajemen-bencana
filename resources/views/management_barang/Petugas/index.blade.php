@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <div>
            <h2 class="text-xl font-bold">Data Petugas</h2>
            <p class="text-gray-500 text-sm">
                Kelola data petugas 
            </p>
        </div>

        <a href="{{ route('admin.management_barang.petugas.create') }}"
           class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block">
            + Tambah Petugas
        </a>

    </div>

<form method="GET" action="{{ route('admin.management_barang.petugas.index') }}">
    <div class="flex gap-4 mb-6">

        <input type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari berdasarkan nama petugas"
            class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">

        <button type="submit" 
            class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
            Cari
        </button>

    </div>
</form>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-center">No</th>
                    <th class="text-left pl-4">Nama Petugas</th>
                    <th class="text-left">Jabatan</th>
                    <th class="text-left">No HP</th>
                    <th class="text-left">Tahun</th> {{-- 🔹 tambahan --}}
                    <th class="text-left">Status</th> {{-- 🔹 tambahan --}}
                    <th class="text-left">Nama Posko</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $key => $item)
                <tr class="border-t">
                    {{-- 🔥 nomor biar sesuai pagination --}}
                    <td class="p-3 text-center">
                        {{ $data->firstItem() + $key }}
                    </td>

                    <td class="p-3 pl-4">{{ $item->nama_petugas }}</td>
                    <td class="p-3">{{ $item->jabatan }}</td>
                    <td class="p-3">{{ $item->no_hp }}</td>

                    {{-- Tahun --}}
                    <td class="p-3">{{ $item->tahun }}</td>

                    {{-- Status dengan badge --}}
                    <td class="p-3">
                        @if($item->status == 'aktif')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
                                Aktif
                            </span>
                        @else
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">
                                Nonaktif
                            </span>
                        @endif
                    </td>

                    <td class="p-3">{{ $item->posko->nama_posko ?? '-' }}</td>

                    {{-- AKSI --}}
                    <td class="p-3 flex justify-center gap-2">

                        {{-- Edit --}}
                        <a href="{{ route('management_barang.petugas.edit', $item->id) }}"
                           class="text-blue-500 hover:text-blue-700">
                            ✏️
                        </a>

                        {{-- Hapus --}}
                        <form action="{{ route('management_barang.petugas.destroy', $item->id) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin mau hapus data ini?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="text-red-500 hover:text-red-700">
                                🗑️
                            </button>
                        </form>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center p-4 text-gray-500">
                        Data belum tersedia
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

    <div class="flex justify-between items-center mt-6 text-sm">

        <p class="text-gray-500">
            Menampilkan {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }}
            dari {{ $data->total() ?? 0 }} data
        </p>

        <div>
            {{ $data->links() }}
        </div>

    </div>

</div>

@endsection