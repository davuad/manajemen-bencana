@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">

            <div>
                <h2 class="text-xl font-bold">KATEGORI BANTUAN</h2>
                <p class="text-gray-500 text-sm">
                    Data kategori bantuan berdasarkan sumber
                </p>
            </div>

            <a href="{{ route('admin.kategori_bantuan.create') }}" class="bg-indigo-700 text-white px-4 py-2 rounded-lg">
                + Tambah Kategori
            </a>

        </div>

        {{-- SEARCH --}}
        <form method="GET">
            <div class="flex gap-4 mb-6">

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori bantuan..."
                    class="flex-1 border rounded-lg px-4 py-2">

                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
                    Cari
                </button>

            </div>
        </form>

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-center">No</th>
                        <th class="text-center">Sumber</th>
                        <th class="text-center">Nama Kategori</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($kategori as $key => $k)
                        <tr class="border-t hover:bg-gray-50">

                            <td class="p-3 text-center">
                                {{ $kategori->firstItem() + $key }}
                            </td>

                            <td class="text-center font-medium">
                                {{ $k->sumber->nama_sumber ?? '-' }}
                            </td>

                            <td class="text-center">
                                {{ $k->nama_kategori }}
                            </td>

                            <td class="text-center">
                                {{ $k->keterangan }}
                            </td>

                            <td class="flex gap-2 py-3">

                                {{-- EDIT --}}
                                <a href="{{ route('admin.kategori_bantuan.edit', $k->id) }}" class="text-blue-500">
                                    ✏️
                                </a>

                                {{-- DELETE --}}
                                <form action="{{ route('admin.kategori_bantuan.destroy', $k->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus data?')">
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
                            <td colspan="5" class="text-center p-4 text-gray-500">
                                Data kategori bantuan belum ada
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="mt-6">
            {{ $kategori->withQueryString()->links() }}
        </div>

    </div>
@endsection
