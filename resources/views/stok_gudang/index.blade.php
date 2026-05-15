@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold">STOK GUDANG</h2>
            <p class="text-gray-500 text-sm">
                Data stok barang pada setiap gudang
            </p>
        </div>

        <a href="{{ route('stok_gudang.create') }}" 
           class="bg-indigo-700 text-white px-4 py-2 rounded-lg">
            + Tambah Stok
        </a>
    </div>

    {{-- SEARCH --}}
    <form method="GET">
        <div class="flex gap-4 mb-6">

            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari gudang..."
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
                    <th class="text-center">Gudang</th>
                    <th class="text-center">Barang</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Kondisi</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($stok as $key => $s)
                <tr class="border-t hover:bg-gray-50">

                    <td class="p-3 text-center">
                        {{ $stok->firstItem() + $key }}
                    </td>

                    <td class="text-center font-medium">
                        {{ $s->gudang->nama_gudang ?? '-' }}
                    </td>

                    <td class="text-center">
                        {{ $s->barang->nama_barang ?? '-' }}
                    </td>

                    <td class="text-center">
                        {{ number_format($s->jumlah_stok) }}
                    </td>

                    <td class="text-center">
                        @if($s->kondisi_barang == 'baik')
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">
                                Baik
                            </span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">
                                Rusak
                            </span>
                        @endif
                    </td>

                    <td class="text-center">
                        {{ $s->keterangan ?? '-' }}
                    </td>

                    <td class="flex gap-2 py-3">

                        {{-- EDIT --}}
                        <a href="{{ route('stok_gudang.edit', $s->id) }}"
                           class="text-blue-500">
                            ✏️
                        </a>

                        {{-- DELETE --}}
                        <form action="{{ route('stok_gudang.destroy', $s->id) }}" method="POST"
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
                    <td colspan="7" class="text-center p-4 text-gray-500">
                        Data stok belum ada
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

    {{-- PAGINATION --}}
    <div class="mt-6">
        {{ $stok->withQueryString()->links() }}
    </div>

</div>

@endsection